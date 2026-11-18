<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // 3. Statistik Weekly Overview (PIJAR & SUAR Mingguan)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $weeklyTerlaksana = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')->count();
        $weeklyMenunggu = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'pending_approval')->count();
        $weeklyBatal = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['cancelled', 'revision_requested'])->count();

        $weeklyPijar = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('is_external', false)->count();
        $weeklySuar = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('is_external', true)->count();

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

        // 5. Grafik Revisi Pak Ridho: PIJAR vs SUAR (5 Tahun: 2026-2027 s.d. 2030-2031)
        $daftarTahun = ['2026-2027', '2027-2028', '2028-2029', '2029-2030', '2030-2031'];
        $chartPijar  = [];
        $chartSuar   = [];

        foreach ($daftarTahun as $th) {
            $chartPijar[] = Schedule::where('tahun_ajaran', $th)->where('is_external', false)->count();
            $chartSuar[]  = Schedule::where('tahun_ajaran', $th)->where('is_external', true)->count();
        }

        // 6. Grafik Revisi Pak Ridho: Perbandingan SUAR per Prodi (Terbanyak & Tersedikit)
        $suarPerProdi = Course::leftJoin('schedules', function ($join) {
                $join->on('courses.id', '=', 'schedules.course_id')
                     ->where('schedules.is_external', '=', true);
            })
            ->select('courses.prodi', DB::raw('COUNT(schedules.id) as total_suar'))
            ->groupBy('courses.prodi')
            ->orderByDesc('total_suar')
            ->get();

        $prodiLabels = $suarPerProdi->pluck('prodi')->toArray();
        $prodiCounts = $suarPerProdi->pluck('total_suar')->toArray();

        // 7. Pemantauan Unggahan Dokumen Dosen/Praktisi (Sudah vs Belum Unggah RPS/Modul)
        $jadwalDisetujuiList = Schedule::with('course')->where('status', 'approved')->get();
        $sudahUnggah = $jadwalDisetujuiList->filter(fn($s) => !empty($s->dokumen_rps) || !empty($s->dokumen_bahan_ajar) || !empty($s->link_modul_ajar));
        $belumUnggah = $jadwalDisetujuiList->filter(fn($s) => empty($s->dokumen_rps) && empty($s->dokumen_bahan_ajar) && empty($s->link_modul_ajar));

        // 8. Data Dropdown untuk Modal
        $rooms   = Room::all();
        $courses = Course::all();

        // 9. Query Tabel Aktivitas Pengajuan Terbaru
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
            'weeklyTerlaksana', 'weeklyMenunggu', 'weeklyBatal', 'weeklyPijar', 'weeklySuar',
            'monthlyTotal', 'monthlyTerlaksana', 'monthlyBelum', 'monthlyEksternal', 'monthlyPersentase',
            'daftarTahun', 'chartPijar', 'chartSuar', 'prodiLabels', 'prodiCounts', 'suarPerProdi',
            'sudahUnggah', 'belumUnggah',
            'rooms', 'courses', 'recentSchedules', 'search'
        ));
    }

    // Halaman Manajemen Jadwal & Approval
    public function manajemenJadwal(Request $request)
    {
        $search      = $request->input('search');
        $status      = $request->input('status');
        $tahunAjaran = $request->input('tahun_ajaran');
        $semester    = $request->input('semester_jenis');

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
            ->when($tahunAjaran, fn($query, $tahunAjaran) => $query->where('tahun_ajaran', $tahunAjaran))
            ->when($semester, fn($query, $semester) => $query->where('semester_jenis', $semester))
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('pijar.manajemen-jadwal', compact(
            'schedules', 'rooms', 'courses',
            'totalJadwal', 'menungguApprove', 'jadwalDisetujui', 'jadwalRevisi',
            'search', 'status', 'tahunAjaran', 'semester'
        ));
    }

    // Simpan Tambah Jadwal Cepat (Dengan Logika Semester & Tahun Ajaran)
    public function store(Request $request)
    {
        $request->validate([
            'course_id'          => 'required|exists:courses,id',
            'room_id'            => 'required|exists:rooms,id',
            'dosen_pengajar'     => 'required|string|max:255',
            'peran_pengaju'      => 'required|string|max:100',
            'tahun_ajaran'       => 'nullable|string|max:20',
            'semester_jenis'     => 'nullable|in:Ganjil,Genap',
            'semester_angka'     => 'nullable|integer|min:1|max:8',
            'status'             => 'required|in:pending_approval,approved,revision_requested,cancelled',
            'dokumen_bahan_ajar' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('dokumen_bahan_ajar')) {
            $filePath = $request->file('dokumen_bahan_ajar')->store('bahan_ajar_pijar', 'public');
        }

        $isExternal = $request->has('is_external');

        $schedule = Schedule::create([
            'course_id'          => $request->course_id,
            'room_id'            => $request->room_id,
            'submitted_by'       => Auth::id(),
            'dosen_pengajar'     => $request->dosen_pengajar,
            'peran_pengaju'      => $request->peran_pengaju,
            'jenis_program'      => $isExternal ? 'SUAR' : 'PIJAR',
            'tahun_ajaran'       => $request->tahun_ajaran ?? '2026-2027',
            'semester_jenis'     => $request->semester_jenis ?? 'Ganjil',
            'semester_angka'     => $request->semester_angka ?? 1,
            'dokumen_bahan_ajar' => $filePath,
            'tanggal_pengajuan'  => now(),
            'status'             => $request->status,
            'is_external'        => $isExternal,
        ]);

        if ($request->status === 'approved') {
            $this->autoCreateLecturerAccount($schedule);
        }

        return back()->with('success', 'Jadwal semester dan berkas bahan ajar berhasil disimpan ke database!');
    }

    // Update / Review Pengajuan Jadwal + Otomatis Buat Akun Email Kampus Dosen saat ACC
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'room_id'               => 'required|exists:rooms,id',
            'dosen_pengajar'        => 'required|string|max:255',
            'status'                => 'required|in:pending_approval,approved,revision_requested,cancelled',
            'alasan_penolakan'      => 'nullable|string',
            'catatan_admin'         => 'nullable|string',
            'nomor_iks'             => 'nullable|string|max:100',
            'status_presensi_pijar' => 'nullable|string|max:100',
            'dosen_email_kampus'    => 'nullable|email',
        ]);

        $schedule->update([
            'room_id'               => $request->room_id,
            'dosen_pengajar'        => $request->dosen_pengajar,
            'status'                => $request->status,
            'alasan_penolakan'      => $request->status === 'cancelled' ? $request->alasan_penolakan : null,
            'catatan_admin'         => $request->catatan_admin,
            'nomor_iks'             => $request->nomor_iks ?? $schedule->nomor_iks,
            'status_presensi_pijar' => $request->status_presensi_pijar ?? $schedule->status_presensi_pijar,
            'dosen_email_kampus'    => $request->dosen_email_kampus ?? $schedule->dosen_email_kampus,
        ]);

        $pesanAkun = '';
        if ($request->status === 'approved') {
            $emailDibuat = $this->autoCreateLecturerAccount($schedule);
            $pesanAkun = " Akun dosen aktif: {$emailDibuat} (Sandi awal: Pradita2026!).";
        }

        return back()->with('success', 'Keputusan persetujuan & kelengkapan SOP berhasil disimpan!' . $pesanAkun);
    }

    // Helper: Otomatis buat akun email kampus dosen/praktisi setelah ACC Admin
    private function autoCreateLecturerAccount(Schedule $schedule): string
    {
        $namaPengajar = $schedule->praktisi_nama ?: $schedule->dosen_pengajar;
        $slugNama     = trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '.', trim($namaPengajar))), '.');
        $emailKampus  = $schedule->dosen_email_kampus ?: ($slugNama . '@pradita.ac.id');

        $schedule->update(['dosen_email_kampus' => $emailKampus]);

        User::firstOrCreate(
            ['email' => $emailKampus],
            [
                'name'                 => $namaPengajar,
                'username'             => $slugNama,
                'password'             => Hash::make('Pradita2026!'),
                'role'                 => $schedule->is_external ? 'praktisi' : 'dosen',
                'prodi'                => $schedule->course->prodi ?? null,
                'is_active'            => true,
                'must_change_password' => true,
            ]
        );

        ActivityLog::create([
            'user_id'        => Auth::id(),
            'email_pengguna' => Auth::user()->email,
            'aksi'           => 'ACC Jadwal & Buat Akun Pengajar',
            'keterangan'     => "ACC {$schedule->course->nama_mk} dan aktivasi akun {$emailKampus} (wajib reset sandi awal).",
        ]);

        return $emailKampus;
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

    // Update Status Pengajaran Eksternal + Pelaporan Pasca-Pelaksanaan (Link Google Drive Dokumentasi & Evaluasi)
    public function updateStatusEksternal(Request $request, Schedule $schedule)
    {
        $request->validate([
            'status_eksternal'            => 'required|in:ready_to_teach,ongoing,completed,pending',
            'nomor_iks'                   => 'nullable|string|max:100',
            'status_presensi_pijar'       => 'nullable|string|max:100',
            'tautan_evaluasi'             => 'nullable|string|max:255',
            'link_gdrive_dokumentasi'     => 'nullable|url|max:255',
            'jumlah_pertemuan_terlaksana' => 'nullable|integer|min:0|max:16',
        ]);

        $schedule->update([
            'status_eksternal'            => $request->status_eksternal,
            'nomor_iks'                   => $request->nomor_iks ?? $schedule->nomor_iks,
            'status_presensi_pijar'       => $request->status_presensi_pijar ?? $schedule->status_presensi_pijar,
            'tautan_evaluasi'             => $request->tautan_evaluasi ?? $schedule->tautan_evaluasi,
            'link_gdrive_dokumentasi'     => $request->link_gdrive_dokumentasi ?? $schedule->link_gdrive_dokumentasi,
            'jumlah_pertemuan_terlaksana' => $request->jumlah_pertemuan_terlaksana ?? $schedule->jumlah_pertemuan_terlaksana,
            'status_pelaksanaan'          => $request->status_eksternal === 'completed' ? 'selesai' : ($request->status_eksternal === 'ongoing' ? 'berlangsung' : 'belum_mulai'),
            'status_bap'                  => $request->status_eksternal === 'completed' ? 'Selesai Mengajar - Siap Pencairan Honor' : $schedule->status_bap,
        ]);

        return back()->with('success', 'Status pengajaran & pelaporan pasca-pelaksanaan berhasil diperbarui!');
    }

    // Halaman Manajemen Akun Pengguna
    public function manajemenAkun(Request $request)
    {
        $search = $request->input('search');
        $role   = $request->input('role');
        $status = $request->input('status');
        $prodi  = $request->input('prodi');

        $totalPengguna = User::count();
        $totalKaprodi  = User::where('role', 'kaprodi')->count();
        $totalSuar     = User::whereIn('role', ['admin', 'admin_suar', 'admin_pijar'])->count();
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
            'role'     => 'required|in:admin,kaprodi,dosen,praktisi,admin_suar,admin_kerma,admin_pijar',
            'prodi'    => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name'                 => $request->name,
            'username'             => $request->username ?: explode('@', $request->email)[0],
            'nip_nidn'             => $request->nip_nidn,
            'email'                => $request->email,
            'role'                 => $request->role,
            'prodi'                => $request->prodi,
            'password'             => Hash::make($request->password),
            'is_active'            => true,
            'must_change_password' => in_array($request->role, ['dosen', 'praktisi']),
        ]);

        return back()->with('success', 'Akun pengguna baru berhasil didaftarkan ke sistem!');
    }

    // Update Akun Pengguna
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'nullable|string|max:100',
            'nip_nidn'  => 'nullable|string|max:50',
            'role'      => 'required|in:admin,kaprodi,dosen,praktisi,admin_suar,admin_kerma,admin_pijar',
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
        $bulan    = $request->input('bulan');
        $tahun    = $request->input('tahun', date('Y'));
        $prodi    = $request->input('prodi');
        $pengajar = $request->input('pengajar');
        $mitra    = $request->input('mitra');
        $search   = $request->input('search');

        $completedQuery = Schedule::with(['course', 'room'])
            ->where('is_external', true)
            ->where('status_eksternal', 'completed');

        $filteredQuery = (clone$completedQuery)
            ->when($bulan, fn($q,$bulan) => $q->whereMonth('tanggal_pengajuan',$bulan))
            ->when($tahun, fn($q,$tahun) => $q->whereYear('tanggal_pengajuan',$tahun))
            ->when($prodi, fn($q,$prodi) => $q->whereHas('course', fn($cq) => $cq->where('prodi',$prodi)))
            ->when($pengajar, fn($q,$pengajar) => $q->where('dosen_pengajar', 'like', "%{$pengajar}%"))
            ->when($mitra, fn($q,$mitra) => $q->where('mitra_instansi', 'like', "%{$mitra}%"))
            ->when($search, function ($q, $search) {$q->where(function ($sub) use ($search) {
                    $sub->where('dosen_pengajar', 'like', "%{$search}%")
                        ->orWhere('mitra_instansi', 'like', "%{$search}%")
                        ->orWhereHas('course', fn($cq) =>$cq->where('nama_mk', 'like', "%{$search}%")->orWhere('kode_mk', 'like', "%{$search}%"));
                });
            });

        $allFiltered   = (clone$filteredQuery)->get();
        $totalSesi     =$allFiltered->count();
        $totalPraktisi =$allFiltered->unique('dosen_pengajar')->count();
        $totalMitra    =$allFiltered->whereNotNull('mitra_instansi')->unique('mitra_instansi')->count();
        $totalProdi    =$allFiltered->pluck('course.prodi')->unique()->count();
        $totalJam      =$allFiltered->sum(fn($item) =>$item->course->sks ?? 3);
        $rataRataJam   =$totalSesi > 0 ? round($totalJam / $totalSesi, 1) : 3;

        $minggu = [
            1 => $allFiltered->filter(fn($i) => $i->tanggal_pengajuan->day <= 7)->count(),
            2 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 8 && $i->tanggal_pengajuan->day <= 14)->count(),
            3 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 15 && $i->tanggal_pengajuan->day <= 21)->count(),
            4 =>$allFiltered->filter(fn($i) =>$i->tanggal_pengajuan->day >= 22)->count(),
        ];
        $maxMingguVal = max(1, max($minggu));$puncakMinggu = array_keys($minggu, max($minggu))[0] ?? 1;
        $persenPuncak =$totalSesi > 0 ? round((max($minggu) /$totalSesi) * 100, 1) : 0;

        $topProdi =$allFiltered->groupBy(fn($i) =>$i->course->prodi ?? 'Umum')
            ->map(fn($group) =>$group->count())
            ->sortDesc()
            ->take(5);
        $maxProdiVal = max(1,$topProdi->max() ?? 1);

        $topMitra =$allFiltered->whereNotNull('mitra_instansi')
            ->groupBy('mitra_instansi')
            ->map(fn($group) =>$group->count())
            ->sortDesc()
            ->take(5);

        $listProdi = Course::distinct()->pluck('prodi');
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
            fputcsv($file, ['No', 'Tahun Ajaran', 'Semester', 'Kode MK', 'Nama Mata Kuliah', 'Program Studi', 'Pengajar Eksternal', 'Instansi Mitra', 'Pertemuan Terlaksana', 'Link Dokumentasi Google Drive', 'Status']);

            foreach ($schedules as $idx =>$item) {
                fputcsv($file, [
                    $idx + 1,$item->tahun_ajaran ?? '2026-2027',
                    ($item->semester_jenis ?? 'Ganjil') . ' (Sem. ' . ($item->semester_angka ?? 1) . ')',
                    $item->course->kode_mk ?? '-',
                    $item->course->nama_mk ?? '-',
                    $item->course->prodi ?? '-',
                    $item->dosen_pengajar,$item->mitra_instansi ?? '-',
                    ($item->jumlah_pertemuan_terlaksana ?? 0) . ' Pertemuan',
                    $item->link_gdrive_dokumentasi ?? '-',
                    'Completed',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200,$headers);
    }

    public function storeRoom(Request $request)
{
    $request->validate([
        'kode_ruang' => 'required|string|max:50',
        'nama_ruang' => 'required|string|max:150',
        'kapasitas'  => 'required|integer|min:1',
    ]);

    Room::create([
        'kode_ruang'   => $request->kode_ruang,
        'nama_ruang'   => $request->nama_ruang,
        'kapasitas'    => $request->kapasitas,
        'is_available' => true,
    ]);

    return back()->with('success', 'Data Master Ruangan baru berhasil ditambahkan!');
}

public function storeCourse(Request $request)
{
    $request->validate([
        'kode_mk'  => 'required|string|max:50',
        'nama_mk'  => 'required|string|max:200',
        'prodi'    => 'required|string|max:150',
        'sks'      => 'required|integer|min:1|max:6',
        'semester' => 'required|integer|min:1|max:8',
    ]);

    Course::create([
        'kode_mk'  => $request->kode_mk,
        'nama_mk'  => $request->nama_mk,
        'prodi'    => $request->prodi,
        'sks'      => $request->sks,
        'semester' => $request->semester,
    ]);

    return back()->with('success', 'Data Master Mata Kuliah baru berhasil ditambahkan!');
}

// Unduh Template Excel/CSV untuk Dosen, Kaprodi, & Peran
    public function downloadTemplateUsers()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Template_Impor_Dosen_dan_Peran.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 agar otomatis rapi saat dibuka di Microsoft Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['nama_lengkap', 'email_kampus', 'nidn_nip', 'role', 'prodi', 'password_awal']);
            fputcsv($file, ['Dr. Budi Santoso, M.Kom.', 'budi.santoso@pradita.ac.id', '0412088101', 'dosen', 'Business Information System', 'Pradita2026!']);
            fputcsv($file, ['Rina Marlina, S.T., M.T.', 'rina.marlina@pradita.ac.id', '0412088102', 'kaprodi', 'Informatics', 'Pradita2026!']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Proses Impor Excel/CSV Akun Dosen, Kaprodi, & Peran
    public function importUsersCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path   = $request->file('file_csv')->getRealPath();
        $lines  = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $berhasil = 0;

        foreach ($lines as $idx => $line) {
            if ($idx === 0) continue; // Lewati baris header

            // Hilangkan BOM jika ada & dukung pemisah koma (,) atau titik koma (;) dari Excel
            $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
            $delimiter = str_contains($line, ';') ? ';' : ',';
            $row = str_getcsv($line, $delimiter);

            $nama  = trim($row[0] ?? '');
            $email = trim($row[1] ?? '');
            if (!$nama || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) continue;

            $nip      = trim($row[2] ?? '');
            $roleRaw  = strtolower(trim($row[3] ?? 'dosen'));
            $role     = in_array($roleRaw, ['admin', 'kaprodi', 'dosen', 'praktisi', 'admin_kerma', 'admin_pijar', 'admin_suar']) ? $roleRaw : 'dosen';
            $prodi    = trim($row[4] ?? '');
            $passAwal = trim($row[5] ?? '') ?: 'Pradita2026!';

            User::updateOrCreate(
                ['email' => $email],
                [
                    'name'                 => $nama,
                    'username'             => explode('@', $email)[0],
                    'nip_nidn'             => $nip ?: null,
                    'role'                 => $role,
                    'prodi'                => $prodi ?: null,
                    'password'             => Hash::make($passAwal),
                    'is_active'            => true,
                    'must_change_password' => in_array($role, ['dosen', 'praktisi']),
                ]
            );
            $berhasil++;
        }

        return back()->with('success', "Berhasil mengimpor {$berhasil} data akun Dosen / Peran dari file Excel/CSV!");
    }

    // Unduh Template Excel/CSV untuk Master Mata Kuliah
    public function downloadTemplateCourses()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Template_Impor_Mata_Kuliah.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['kode_mk', 'nama_mk', 'prodi', 'sks', 'semester']);
            fputcsv($file, ['IS4012', 'Arsitektur Enterprise', 'Business Information System', '3', '5']);
            fputcsv($file, ['IF3021', 'Rekayasa Perangkat Lunak', 'Informatics', '3', '4']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Proses Impor Excel/CSV Master Mata Kuliah
    public function importCoursesCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $path   = $request->file('file_csv')->getRealPath();
        $lines  = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $berhasil = 0;

        foreach ($lines as $idx => $line) {
            if ($idx === 0) continue;

            $line = preg_replace('/^\xEF\xBB\xBF/', '', $line);
            $delimiter = str_contains($line, ';') ? ';' : ',';
            $row = str_getcsv($line, $delimiter);

            $kodeMk = trim($row[0] ?? '');
            $namaMk = trim($row[1] ?? '');
            if (!$kodeMk || !$namaMk) continue;

            Course::updateOrCreate(
                ['kode_mk' => $kodeMk],
                [
                    'nama_mk'  => $namaMk,
                    'prodi'    => trim($row[2] ?? 'Business Information System'),
                    'sks'      => (int) ($row[3] ?? 3),
                    'semester' => (int) ($row[4] ?? 1),
                ]
            );
            $berhasil++;
        }

        return back()->with('success', "Berhasil mengimpor {$berhasil} data Master Mata Kuliah dari file Excel/CSV!");
    }
}