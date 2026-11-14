<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Halaman Manajemen Jadwal & Approval (Diletakkan setelah method index)
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
    }

    // Simpan Tambah Jadwal Cepat
    public function store(Request $request)
    {
        $request->validate([
            'course_id'      => 'required|exists:courses,id',
            'room_id'        => 'required|exists:rooms,id',
            'dosen_pengajar' => 'required|string|max:255',
            'peran_pengaju'  => 'required|string|max:100',
            'status'         => 'required|in:pending_approval,approved,revision_requested,cancelled',
        ]);

        Schedule::create([
            'course_id'         => $request->course_id,
            'room_id'           => $request->room_id,
            'submitted_by'      => Auth::id(),
            'dosen_pengajar'    => $request->dosen_pengajar,
            'peran_pengaju'     => $request->peran_pengaju,
            'tanggal_pengajuan' => now(),
            'status'            => $request->status,
            'is_external'       => $request->has('is_external'),
        ]);

        return back()->with('success', 'Jadwal baru berhasil ditambahkan ke database!');
    }

    // Update / Review Pengajuan Jadwal
    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'room_id'        => 'required|exists:rooms,id',
            'dosen_pengajar' => 'required|string|max:255',
            'status'         => 'required|in:pending_approval,approved,revision_requested,cancelled',
            'catatan_admin'  => 'nullable|string',
        ]);

        $schedule->update([
            'room_id'        => $request->room_id,
            'dosen_pengajar' => $request->dosen_pengajar,
            'status'         => $request->status,
            'catatan_admin'  => $request->catatan_admin,
        ]);

        return back()->with('success', 'Keputusan persetujuan jadwal berhasil disimpan secara permanen!');
    }

    // Halaman Monitoring Jadwal Pihak Eksternal
    public function jadwalEksternal(Request $request)
    {
        $search = $request->input('search');
        $prodi  = $request->input('prodi');
        $status = $request->input('status_eksternal');
        $mitra  = $request->input('mitra');

        $baseQuery = Schedule::where('is_external', true);

        $totalEksternal   = (clone $baseQuery)->count();
        $readyToTeach     = (clone $baseQuery)->where('status_eksternal', 'ready_to_teach')->count();
        $sedangBerlangsung = (clone $baseQuery)->where('status_eksternal', 'ongoing')->count();
        $sudahTerlaksana  = (clone $baseQuery)->where('status_eksternal', 'completed')->count();
        $belumTerlaksana  = (clone $baseQuery)->where('status_eksternal', 'pending')->count();

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
            'status_eksternal' => 'required|in:ready_to_teach,ongoing,completed,pending',
        ]);

        $schedule->update([
            'status_eksternal' => $request->status_eksternal,
            'status_bap'       => $request->status_eksternal === 'completed' ? 'Terverifikasi BAP & e-Presensi' : $schedule->status_bap,
        ]);

        return back()->with('success', 'Status pengajaran praktisi eksternal berhasil diperbarui!');
    }
}