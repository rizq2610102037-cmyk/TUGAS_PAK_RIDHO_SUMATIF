<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // 3. Statistik Weekly Overview (7 hari terakhir)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $weeklyTerlaksana = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'approved')
            ->count();
        $weeklyMenunggu = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->where('status', 'pending_approval')
            ->count();
        $weeklyBatal = Schedule::whereBetween('tanggal_pengajuan', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['cancelled', 'revision_requested'])
            ->count();

        // 4. Statistik Monthly Overview (Bulan berjalan)
        $currentMonth = Carbon::now()->month;
        $currentYear  = Carbon::now()->year;

        $monthlyTotal = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->count();
        $monthlyTerlaksana = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->where('status', 'approved')
            ->count();
        $monthlyBelum = max(0, $monthlyTotal - $monthlyTerlaksana);
        $monthlyEksternal = Schedule::whereMonth('tanggal_pengajuan', $currentMonth)
            ->whereYear('tanggal_pengajuan', $currentYear)
            ->where('is_external', true)
            ->count();

        $monthlyPersentase = $monthlyTotal > 0 ? round(($monthlyTerlaksana / $monthlyTotal) * 100) : 0;

        // 5. Query Tabel Aktivitas Pengajuan Terbaru (dengan pengelompokan pencarian yang aman)
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
            'totalRuang',
            'ruangTersedia',
            'dosenAktif',
            'totalJadwal',
            'menungguApprove',
            'jadwalDisetujui',
            'jadwalEksternal',
            'weeklyTerlaksana',
            'weeklyMenunggu',
            'weeklyBatal',
            'monthlyTotal',
            'monthlyTerlaksana',
            'monthlyBelum',
            'monthlyEksternal',
            'monthlyPersentase',
            'recentSchedules',
            'search'
        ));
    }
}