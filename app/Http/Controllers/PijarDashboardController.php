<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PijarDashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Indikator Banner Atas
        $totalRuang    = Room::count();
        $ruangTersedia = Room::where('is_available', true)->count();
        $dosenAktif    = Schedule::where('status', 'approved')->distinct('dosen_pengajar')->count('dosen_pengajar');

        // 2. 4 Kartu Metrik Utama
        $totalJadwal     = Schedule::count();
        $menungguApprove = Schedule::where('status', 'pending_approval')->count();
        $jadwalDisetujui = Schedule::where('status', 'approved')->count();
        $jadwalEksternal = Schedule::where('is_external', true)->count();

        // 3. Statistik Weekly Overview
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $weeklyTerlaksana = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')->count();
        $weeklyMenunggu = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'pending_approval')->count();
        $weeklyBatal = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['cancelled', 'revision_requested'])->count();

        // 4. Statistik Monthly Overview
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $monthlyTotal = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)->count();
        $monthlyTerlaksana = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->where('status', 'approved')->count();
        $monthlyBelum = max(0, $monthlyTotal - $monthlyTerlaksana);
        $monthlyEksternal = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->where('is_external', true)->count();

        $monthlyPersentase = $monthlyTotal > 0 ? round(($monthlyTerlaksana / $monthlyTotal) * 100) : 0;

        // 5. Data Dropdown untuk Modal
        $rooms   = Room::all();
        $courses = Course::all();

        // 6. Query Tabel Aktivitas Pengajuan Terbaru
        $recentSchedules = Schedule::with(['course', 'room'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('dosen_pengajar', 'like', "%{$search}%")
                      ->orWhereHas('course', function ($courseQuery) use ($search) {
                          $courseQuery->where('nama_mk', 'like', "%{$search}%")
                                       ->orWhere('kode_mk', 'like', "%{$search}%");
                      })
                      ->orWhereHas('room', function ($roomQuery) use ($search) {
                          $roomQuery->where('kode_ruang', 'like', "%{$search}%");
                      });
                });
            })
            ->orderByDesc('tanggal_pengajuan')
            ->get();

        return view('pijar.dashboard', compact(
            'totalRuang', 'ruangTersedia', 'dosenAktif',
            'totalJadwal', 'menungguApprove', 'jadwalDisetujui', 'jadwalEksternal',
            'weeklyTerlaksana', 'weeklyMenunggu', 'weeklyBatal',
            'monthlyTotal', 'monthlyTerlaksana', 'monthlyBelum', 'monthlyEksternal', 'monthlyPersentase',
            'rooms', 'courses', 'recentSchedules', 'search'
        ));
    }

    // Halaman Manajemen Jadwal & Approval
    public function manajemenJadwal(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $rooms   = Room::all();
        $courses = Course::all();

        $totalJadwal     = Schedule::count();
        $menungguApprove = Schedule::where('status', 'pending_approval')->count();
        $jadwalDisetujui = Schedule::where('status', 'approved')->count();
        $jadwalRevisi    = Schedule::whereIn('status', ['revision_requested', 'cancelled'])->count();

        $schedules = Schedule::with(['course', 'room'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('dosen_pengajar', 'like', "%{$search}%")
                      ->orWhereHas('course', fn($cq) => $cq->where('nama_mk', 'like', "%{$search}%")->orWhere('kode_mk', 'like', "%{$search}%")->orWhere('prodi', 'like', "%{$search}%"))
                      ->orWhereHas('room', fn($rq) => $rq->where('kode_ruang', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn($query, $status) => $query->where('status', $status))
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('pijar.manajemen-jadwal', compact(
            'schedules', 'rooms', 'courses',
            'totalJadwal', 'menungguApprove', 'jadwalDisetujui', 'jadwalRevisi',
            'search', 'status'
        ));

        // Hitung kuota pertemuan praktisi per Prodi (Maks 28/tahun) & per MK (Maks 7/MK)
        $kuotaProdi = Schedule::where('is_external', true)
            ->whereIn('status', ['approved', 'pending_approval'])
            ->with('course')
            ->get()
            ->groupBy(fn($s) => $s->course->prodi ?? 'Umum')
            ->map(fn($g) => $g->count());

        $kuotaMk = Schedule::where('is_external', true)
            ->whereIn('status', ['approved', 'pending_approval'])
            ->selectRaw('course_id, count(*) as total')
            ->groupBy('course_id')
            ->pluck('total', 'course_id');
    }

    // Simpan Tambah Jadwal Cepat
    public function store(Request $request)
    {
        $request->validate([
            'course_id'          => 'required|exists:courses,id',
            'room_id'            => 'required|exists:rooms,id',
            'dosen_pengajar'     => 'required|string|max:255',
            'peran_pengaju'      => 'required|string|max:100',
            'status'             => 'required|in:pending_approval,approved,revision_requested,cancelled',
            'dokumen_bahan_ajar' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('dokumen_bahan_ajar')) {
            $filePath = $request->file('dokumen_bahan_ajar')->store('bahan_ajar_pijar', 'public');
        }

        Schedule::create([
            'course_id'          => $request->course_id,
            'room_id'            => $request->room_id,
            'submitted_by'       => Auth::id(),
            'dosen_pengajar'     => $request->dosen_pengajar,
            'peran_pengaju'      => $request->peran_pengaju,
            'dokumen_bahan_ajar' => $filePath,
            'tanggal_pengajuan'  => now(),
            'status'             => $request->status,
            'is_external'        => $request->has('is_external'),
        ]);

        return back()->with('success', 'Jadwal dan berkas bahan ajar berhasil disimpan ke database!');
    }

    // Update / Review Pengajuan Jadwal
   public function update(Request $request, Schedule $schedule)
{
    $request->validate([
        'room_id'               => 'required|exists:rooms,id',
        'dosen_pengajar'        => 'required|string|max:255',
        'status'                => 'required|in:pending_approval,approved,revision_requested,cancelled',
        'catatan_admin'         => 'nullable|string',
        'nomor_iks'             => 'nullable|string|max:100',
        'status_presensi_pijar' => 'nullable|string|max:100',
    ]);

    $schedule->update([
        'room_id'               => $request->room_id,
        'dosen_pengajar'        => $request->dosen_pengajar,
        'status'                => $request->status,
        'catatan_admin'         => $request->catatan_admin,
        'nomor_iks'             => $request->nomor_iks ?? $schedule->nomor_iks,
        'status_presensi_pijar' => $request->status_presensi_pijar ?? $schedule->status_presensi_pijar,
    ]);

    return back()->with('success', 'Keputusan persetujuan & kelengkapan SOP PIJAR berhasil disimpan!');
}

    // Halaman Monitoring Jadwal Pihak Eksternal
    public function jadwalEksternal(Request $request)
    {
        $search = $request->input('search');
        $prodi  = $request->input('prodi');
        $status = $request->input('status_eksternal');
        $mitra  = $request->input('mitra');

        $baseQuery = Schedule::where('is_external', true);

        $totalEksternal    = (clone $baseQuery)->count();
        $readyToTeach      = (clone $baseQuery)->where('status_eksternal', 'ready_to_teach')->count();
        $sedangBerlangsung = (clone $baseQuery)->where('status_eksternal', 'ongoing')->count();
        $sudahTerlaksana   = (clone $baseQuery)->where('status_eksternal', 'completed')->count();
        $belumTerlaksana   = (clone $baseQuery)->where('status_eksternal', 'pending')->count();

        $persenTerlaksana = $totalEksternal > 0 ? round(($sudahTerlaksana / $totalEksternal) * 100, 1) : 0;
        $persenKuota      = $totalEksternal > 0 ? round((($totalEksternal - $belumTerlaksana) / $totalEksternal) * 100, 1) : 0;

        $listProdi = Course::distinct()->pluck('prodi');
        $listMitra = Schedule::where('is_external', true)->whereNotNull('mitra_instansi')->distinct()->pluck('mitra_instansi');

        $externalSchedules = Schedule::with(['course', 'room'])
            ->where('is_external', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('dosen_pengajar', 'like', "%{$search}%")
                      ->orWhere('mitra_instansi', 'like', "%{$search}%")
                      ->orWhereHas('course', fn($cq) => $cq->where('nama_mk', 'like', "%{$search}%")->orWhere('kode_mk', 'like', "%{$search}%"));
                });
            })
            ->when($prodi, fn($q, $prodi) => $q->whereHas('course', fn($cq) => $cq->where('prodi', $prodi)))
            ->when($status, fn($q, $status) => $q->where('status_eksternal', $status))
            ->when($mitra, fn($q, $mitra) => $q->where('mitra_instansi', $mitra))
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('pijar.jadwal-eksternal', compact(
            'externalSchedules', 'totalEksternal', 'readyToTeach',
            'sedangBerlangsung', 'sudahTerlaksana', 'belumTerlaksana',
            'persenTerlaksana', 'persenKuota', 'listProdi', 'listMitra',
            'search', 'prodi', 'status', 'mitra'
        ));
    }

    // Update Status Pengajaran Eksternal dari Panel Kanan
    public function updateStatusEksternal(Request $request, Schedule $schedule)
{
    $request->validate([
        'status_eksternal'      => 'required|in:ready_to_teach,ongoing,completed,pending',
        'nomor_iks'             => 'nullable|string|max:100',
        'status_presensi_pijar' => 'nullable|string|max:100',
        'tautan_evaluasi'       => 'nullable|string|max:255',
    ]);

    $schedule->update([
        'status_eksternal'      => $request->status_eksternal,
        'nomor_iks'             => $request->nomor_iks ?? $schedule->nomor_iks,
        'status_presensi_pijar' => $request->status_presensi_pijar ?? $schedule->status_presensi_pijar,
        'tautan_evaluasi'       => $request->tautan_evaluasi ?? $schedule->tautan_evaluasi,
        'status_bap'            => $request->status_eksternal === 'completed' ? 'Terverifikasi Presensi & Evaluasi PIJAR' : $schedule->status_bap,
    ]);

    return back()->with('success', 'Status pengajaran, IKS, & evaluasi PIJAR berhasil diperbarui!');
}

    // Halaman Manajemen Akun Pengguna
    public function manajemenAkun(Request $request)
    {
        $search = $request->input('search');
        $role   = $request->input('role');
        $status = $request->input('status');
        $prodi  = $request->input('prodi');

        // 4 Kartu Metrik
        $totalPengguna = User::count();
        $totalKaprodi  = User::where('role', 'kaprodi')->count();
        $totalSuar     = User::where('role', 'admin_suar')->count();
        $totalKerma    = User::where('role', 'admin_kerma')->count();
        $persenAktif   = $totalPengguna > 0 ? round((User::where('is_active', true)->count() / $totalPengguna) * 100) : 100;

        $listProdi = User::whereNotNull('prodi')->distinct()->pluck('prodi');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('nip_nidn', 'like', "%{$search}%");
                });
            })
            ->when($role, fn($q, $role) => $q->where('role', $role))
            ->when($status !== null && $status !== '', fn($q) => $q->where('is_active', $status === 'aktif'))
            ->when($prodi, fn($q, $prodi) => $q->where('prodi', $prodi))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('pijar.manajemen-akun', compact(
            'users', 'totalPengguna', 'totalKaprodi', 'totalSuar', 'totalKerma',
            'persenAktif', 'listProdi', 'search', 'role', 'status', 'prodi'
        ));
    }

    // Simpan Tambah Akun Baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'nullable|string|max:100',
            'nip_nidn' => 'nullable|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:kaprodi,admin_suar,admin_kerma,admin_pijar',
            'prodi'    => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'username'  => $request->username ?: explode('@', $request->email)[0],
            'nip_nidn'  => $request->nip_nidn,
            'email'     => $request->email,
            'role'      => $request->role,
            'prodi'     => $request->prodi,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        return back()->with('success', 'Akun pengguna baru berhasil didaftarkan ke sistem!');
    }

    // Update Akun Pengguna (Role, Prodi, Status Aktif, atau Reset Password)
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:100',
            'nip_nidn'  => 'nullable|string|max:50',
            'role'      => 'required|in:kaprodi,admin_suar,admin_kerma,admin_pijar',
            'prodi'     => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'password'  => 'nullable|string|min:6',
        ]);

        $data = [
            'name'      => $request->name,
            'username'  => $request->username,
            'nip_nidn'  => $request->nip_nidn,
            'role'      => $request->role,
            'prodi'     => $request->prodi,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Otorisasi dan profil pengguna berhasil diperbarui!');
    }

    // Halaman Rekapitulasi Pengajaran Pihak Eksternal
    public function rekapPengajaran(Request $request)
    {
        $bulan   =$request->input('bulan');
        $tahun   =$request->input('tahun', date('Y'));
        $prodi   =$request->input('prodi');
        $pengajar =$request->input('pengajar');
        $mitra   =$request->input('mitra');
        $search  =$request->input('search');

        // Base Query: Jadwal Eksternal yang sudah terlaksana / disetujui
        $completedQuery = Schedule::with(['course', 'room'])
            ->where('is_external', true)
            ->where('status_eksternal', 'completed');

        // Terapkan filter jika ada
        $filteredQuery = (clone$completedQuery)
            ->when($bulan, fn($q,$bulan) => $q->whereMonth('tanggal_pengajuan',$bulan))
            ->when($tahun, fn($q,$tahun) => $q->whereYear('tanggal_pengajuan',$tahun))
            ->when($prodi, fn($q,$prodi) => $q->whereHas('course', fn($cq) => $cq->where('prodi',$prodi)))
            ->when($pengajar, fn($q,$pengajar) => $q->where('dosen_pengajar', 'like', "\%{$pengajar}%"))
            ->when($mitra, fn($q,$mitra) => $q->where('mitra_instansi', 'like', "\%{$mitra}%"))
            ->when($search, function ($q, $search) {$q->where(function ($sub) use ($search) {
                    $sub->where('dosen_pengajar', 'like', "\%{$search}%")
                        ->orWhere('mitra_instansi', 'like', "%{$search}%")
                        ->orWhereHas('course', fn($cq) =>$cq->where('nama_mk', 'like', "%{$search}\%")->orWhere('kode_mk', 'like', "\%{$search}%"));
                });
            });

        // 1. 4 Kartu Metrik Utama
        $allFiltered     = (clone$filteredQuery)->get();
        $totalSesi       =$allFiltered->count();
        $totalPraktisi   =$allFiltered->unique('dosen_pengajar')->count();
        $totalMitra      =$allFiltered->whereNotNull('mitra_instansi')->unique('mitra_instansi')->count();
        $totalProdi      =$allFiltered->pluck('course.prodi')->unique()->count();
        $totalJam        =$allFiltered->sum(fn($item) =>$item->course->sks ?? 3);
        $rataRataJam     =$totalSesi > 0 ? round($totalJam / $totalSesi, 1) : 3;

        // 2. Analitik Visual 1: Distribusi per Minggu (Minggu 1 - 4)
        $minggu = [
            1 => $allFiltered->filter(fn($i) => $i->tanggal_pengajuan->day <= 7)->count(), 2 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 8 && $i->tanggal_pengajuan->day <= 14)->count(), 3 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 15 && $i->tanggal_pengajuan->day <= 21)->count(), 4 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 22)->count(),
        ];
        $maxMingguVal = max(1, max($minggu));$puncakMinggu = array_keys($minggu, max($minggu))[0] ?? 1;
        $persenPuncak =$totalSesi > 0 ? round((max($minggu) /$totalSesi) * 100, 1) : 0;

        // 3. Analitik Visual 2: Top 5 Program Studi
        $topProdi =$allFiltered->groupBy(fn($i) =>$i->course->prodi ?? 'Umum')
            ->map(fn($group) =>$group->count())
            ->sortDesc()
            ->take(5);
        $maxProdiVal = max(1,$topProdi->max() ?? 1);

        // 4. Analitik Visual 3: Top 5 Mitra Instansi Terbanyak
        $topMitra =$allFiltered->whereNotNull('mitra_instansi')
            ->groupBy('mitra_instansi')
            ->map(fn($group) =>$group->count())
            ->sortDesc()
            ->take(5);

        // Dropdown Filter
        $listProdi = Course::distinct()->pluck('prodi');

        // Tabel Audit Pengajaran Terlaksana (Pagination)
        $auditSchedules =$filteredQuery->orderByDesc('tanggal_pengajuan')->paginate(10)->withQueryString();

        return view('pijar.rekap-pengajaran', compact(
            'auditSchedules', 'totalSesi', 'totalPraktisi', 'totalMitra',
            'totalProdi', 'totalJam', 'rataRataJam',
            'minggu', 'maxMingguVal', 'puncakMinggu', 'persenPuncak',
            'topProdi', 'maxProdiVal', 'topMitra', 'listProdi',
            'bulan', 'tahun', 'prodi', 'pengajar', 'mitra', 'search'
        ));
    }

    // Export Rekap Pengajaran ke CSV / Excel
    public function exportRekapExcel()
    {
        $schedules = Schedule::with(['course', 'room'])
            ->where('is_external', true)
            ->where('status_eksternal', 'completed')
            ->orderByDesc('tanggal_pengajuan')
            ->get();

        $filename = 'Rekap_Pengajaran_Eksternal_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($schedules) {$file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kode MK', 'Nama Mata Kuliah', 'Program Studi', 'Pengajar Eksternal', 'Instansi Mitra', 'Tanggal Sesi', 'Waktu', 'Durasi (SKS/Jam)', 'Status']);

            foreach ($schedules as $idx =>$item) {
                fputcsv($file, [
                    $idx + 1,$item->course->kode_mk ?? '-',
                    $item->course->nama_mk ?? '-',
                    $item->course->prodi ?? '-',
                    $item->dosen_pengajar,$item->mitra_instansi ?? '-',
                    $item->hari_sesi,$item->waktu_sesi,
                    ($item->course->sks ?? 3) . ' Jam',
                    'Completed',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200,$headers);
    }
}