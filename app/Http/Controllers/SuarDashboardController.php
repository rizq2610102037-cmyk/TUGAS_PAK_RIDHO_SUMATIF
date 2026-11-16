<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Practitioner;
use App\Models\Schedule;
use Illuminate\Http\Request;

class SuarDashboardController extends Controller
{
    // 1. Halaman Utama: Pengajuan Disetujui & Penugasan Praktisi SUAR
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status_eksternal');

        // Pengajuan yang sudah disetujui PIJAR (status = approved)
        $baseQuery = Schedule::where('status', 'approved');

        $totalDisetujui   = (clone $baseQuery)->count();
        $belumDitugaskan  = (clone $baseQuery)->where(function ($q) {
            $q->whereNull('praktisi_nama')->orWhere('status_eksternal', 'pending');
        })->count();
        $siapMengajar     = (clone $baseQuery)->whereIn('status_eksternal', ['ready_to_teach', 'ongoing'])->count();
        $selesaiMengajar  = (clone $baseQuery)->where('status_eksternal', 'completed')->count();
        $totalPraktisiAktif = Practitioner::where('is_active', true)->count();

        // Daftar praktisi aktif untuk pilihan penugasan
        $practitioners = Practitioner::where('is_active', true)->orderBy('nama')->get();

        $schedules = Schedule::with(['course', 'room'])
            ->where('status', 'approved')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('dosen_pengajar', 'like', "%{$search}%")
                      ->orWhere('praktisi_nama', 'like', "%{$search}%")
                      ->orWhere('mitra_instansi', 'like', "%{$search}%")
                      ->orWhereHas('course', fn($cq) => $cq->where('nama_mk', 'like', "%{$search}%")->orWhere('kode_mk', 'like', "%{$search}%")->orWhere('prodi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn($q, $status) => $q->where('status_eksternal', $status))
            ->orderByDesc('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('suar.dashboard', compact(
            'schedules', 'practitioners',
            'totalDisetujui', 'belumDitugaskan', 'siapMengajar',
            'selesaiMengajar', 'totalPraktisiAktif',
            'search', 'status'
        ));
    }

    // Simpan Penugasan & Update Status Praktisi pada Pengajuan SUAR
    public function assignPractitioner(Request $request, Schedule $schedule)
    {
        $request->validate([
            'practitioner_id'  => 'required|exists:practitioners,id',
            'status_eksternal' => 'required|in:pending,ready_to_teach,ongoing,completed',
            'nomor_iks'        => 'nullable|string|max:100',
            'catatan_admin'    => 'nullable|string',
        ]);

        $practitioner = Practitioner::findOrFail($request->practitioner_id);

        $schedule->update([
            'is_external'      => true,
            'praktisi_nama'    => $practitioner->nama,
            'mitra_instansi'   => $practitioner->mitra_instansi,
            'jabatan_praktisi' => $practitioner->jabatan,
            'status_eksternal' => $request->status_eksternal,
            'nomor_iks'        => $request->nomor_iks ?? $schedule->nomor_iks,
            'catatan_admin'    => $request->catatan_admin ?? $schedule->catatan_admin,
        ]);

        return back()->with('success', "Praktisi {$practitioner->nama} berhasil ditugaskan dan status penugasan diperbarui!");
    }

    // 2. Halaman Direktori & Manajemen Data Praktisi
    public function praktisi(Request $request)
    {
        $search   = $request->input('search');
        $keahlian = $request->input('keahlian');

        $totalPraktisi  = Practitioner::count();
        $praktisiAktif  = Practitioner::where('is_active', true)->count();
        $totalMitra     = Practitioner::distinct('mitra_instansi')->count('mitra_instansi');
        $totalKeahlian  = Practitioner::distinct('bidang_keahlian')->count('bidang_keahlian');

        $listKeahlian = Practitioner::distinct()->pluck('bidang_keahlian');

        $practitioners = Practitioner::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('mitra_instansi', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%")
                      ->orWhere('bidang_keahlian', 'like', "%{$search}%");
                });
            })
            ->when($keahlian, fn($q, $keahlian) => $q->where('bidang_keahlian', $keahlian))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('suar.praktisi', compact(
            'practitioners', 'totalPraktisi', 'praktisiAktif',
            'totalMitra', 'totalKeahlian', 'listKeahlian',
            'search', 'keahlian'
        ));
    }

    // Simpan Tambah Praktisi Baru
    public function storePractitioner(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'mitra_instansi'  => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'bidang_keahlian' => 'required|string|max:255',
            'pengalaman'      => 'nullable|string',
            'kontak'          => 'nullable|string|max:100',
        ]);

        Practitioner::create([
            'nama'            => $request->nama,
            'mitra_instansi'  => $request->mitra_instansi,
            'jabatan'         => $request->jabatan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'pengalaman'      => $request->pengalaman,
            'kontak'          => $request->kontak,
            'is_active'       => true,
        ]);

        return back()->with('success', 'Data praktisi baru beserta kompetensi berhasil ditambahkan!');
    }

    // Update Data, Kompetensi, Keahlian & Pengalaman Praktisi
    public function updatePractitioner(Request $request, Practitioner $practitioner)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'mitra_instansi'  => 'required|string|max:255',
            'jabatan'         => 'required|string|max:255',
            'bidang_keahlian' => 'required|string|max:255',
            'pengalaman'      => 'nullable|string',
            'kontak'          => 'nullable|string|max:100',
            'is_active'       => 'required|boolean',
        ]);

        $practitioner->update([
            'nama'            => $request->nama,
            'mitra_instansi'  => $request->mitra_instansi,
            'jabatan'         => $request->jabatan,
            'bidang_keahlian' => $request->bidang_keahlian,
            'pengalaman'      => $request->pengalaman,
            'kontak'          => $request->kontak,
            'is_active'       => $request->is_active,
        ]);

        return back()->with('success', 'Informasi kompetensi dan profil praktisi berhasil diperbarui!');
    }

    // Hapus Data Praktisi
    public function destroyPractitioner(Practitioner $practitioner)
    {
        $nama = $practitioner->nama;
        $practitioner->delete();

        return back()->with('success', "Data praktisi {$nama} berhasil dihapus!");
    }
}