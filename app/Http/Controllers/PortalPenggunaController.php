<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalPenggunaController extends Controller
{
    // =========================================================
    // 1. HALAMAN WAJIB GANTI KATA SANDI AWAL (DOSEN & PRAKTISI)
    // =========================================================
    public function showChangePassword()
    {
        return view('auth.ganti-sandi-awal');
    }

    public function updateChangePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min'       => 'Kata sandi baru minimal 8 karakter.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update([
            'password'             => Hash::make($request->password),
            'must_change_password' => false,
        ]);

        return redirect()->route('dosen.dashboard')
            ->with('success', 'Kata sandi berhasil diperbarui! Selamat datang di Portal Dosen & Praktisi.');
    }

    // =========================================================
    // 2. PORTAL KAPRODI (PENGAJUAN PRAKTISI & PANTAU STATUS ACC)
    // =========================================================
    public function kaprodiDashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->must_change_password) {
            return redirect()->route('password.force');
        }

        // Ambil master data Mata Kuliah & Dosen sesuai prodi Kaprodi (atau semua jika prodi kosong)
        $courses = Course::when($user->prodi, fn($q) => $q->where('prodi', $user->prodi))
            ->orderBy('nama_mk')
            ->get();

        if ($courses->isEmpty()) {
            $courses = Course::orderBy('nama_mk')->get();
        }

        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $schedules = Schedule::with(['course', 'room'])
            ->when($user->prodi, function ($q) use ($user) {
                $q->whereHas('course', fn($c) => $c->where('prodi', $user->prodi));
            })
            ->latest()
            ->paginate(10);

        return view('kaprodi.dashboard', compact('user', 'courses', 'dosens', 'schedules'));
    }

    public function kaprodiStorePengajuan(Request $request)
    {
        $request->validate([
            'course_id'           => 'required|exists:courses,id',
            'dosen_pengajar'      => 'required|string|max:200',
            'dosen_email'         => 'nullable|email|max:200',
            'praktisi_nama'       => 'required|string|max:200',
            'praktisi_email'      => 'required|email|max:200',
            'mitra_instansi'      => 'required|string|max:200',
            'jabatan_praktisi'    => 'required|string|max:200',
            'tahun_ajaran'        => 'required|string|max:30',
            'semester_jenis'      => 'required|in:Ganjil,Genap',
            'semester_angka'      => 'required|integer|min:1|max:8',
            'catatan_pengaju'     => 'nullable|string',
            'dokumen_bahan_ajar'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        // Ambil ruangan default/placeholder sistem karena jadwal difokuskan ke Semester & Tahun Ajaran
        $defaultRoom = Room::firstOrCreate(
            ['kode_ruang' => 'SUAR-FLEX'],
            ['nama_ruang' => 'Kelas Terjadwal Prodi (Fleksibel)', 'kapasitas' => 40, 'is_available' => true]
        );

        $filePath = null;
        if ($request->hasFile('dokumen_bahan_ajar')) {
            $filePath = $request->file('dokumen_bahan_ajar')->store('rps_bahan_ajar', 'public');
        }

        Schedule::create([
            'course_id'          => $request->course_id,
            'room_id'            => $defaultRoom->id,
            'kelas'              => 'Kelas Reguler',
            'dosen_pengajar'     => $request->dosen_pengajar,
            'dosen_email'        => $request->dosen_email,
            'praktisi_nama'      => $request->praktisi_nama,
            'praktisi_email'     => $request->praktisi_email,
            'mitra_instansi'     => $request->mitra_instansi,
            'jabatan_praktisi'   => $request->jabatan_praktisi,
            'tahun_ajaran'       => $request->tahun_ajaran,
            'semester_jenis'     => $request->semester_jenis,
            'semester_angka'     => $request->semester_angka,
            'hari_sesi'          => 'TA ' . $request->tahun_ajaran,
            'waktu_sesi'         => 'Semester ' . $request->semester_jenis,
            'status'             => 'pending',
            'status_eksternal'   => 'pending',
            'catatan_pengaju'    => $request->catatan_pengaju,
            'dokumen_bahan_ajar' => $filePath,
        ]);

        return back()->with('success', 'Pengajuan Praktisi Mengajar berhasil dikirim ke Admin Pusat untuk diverifikasi!');
    }

    // =========================================================
    // 3. PORTAL DOSEN & PRAKTISI (RPS, MODUL 1-13 & LAPORAN G-DRIVE)
    // =========================================================
    public function dosenDashboard()
    {
        $user = Auth::user();

        if ($user->must_change_password) {
            return redirect()->route('password.force');
        }

        $schedules = Schedule::with(['course', 'room'])
            ->where(function ($q) use ($user) {
                $q->where('dosen_email', $user->email)
                  ->orWhere('praktisi_email', $user->email)
                  ->orWhere('dosen_pengajar', 'like', '%' . $user->name . '%')
                  ->orWhere('praktisi_nama', 'like', '%' . $user->name . '%');
            })
            ->latest()
            ->get();

        return view('dosen.dashboard', compact('user', 'schedules'));
    }

    public function dosenUploadPerangkat(Request $request, Schedule $schedule)
    {
        $request->validate([
            'file_rps'           => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_silabus'       => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'file_modul_1_13'    => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:25600',
        ]);

        $data = [];
        if ($request->hasFile('file_rps')) {
            $data['file_rps'] = $request->file('file_rps')->store('perangkat_ajar/rps', 'public');
        }
        if ($request->hasFile('file_silabus')) {
            $data['file_silabus'] = $request->file('file_silabus')->store('perangkat_ajar/silabus', 'public');
        }
        if ($request->hasFile('file_modul_1_13')) {
            $data['file_modul_1_13'] = $request->file('file_modul_1_13')->store('perangkat_ajar/modul', 'public');
        }

        if (!empty($data)) {
            $schedule->update($data);
        }

        return back()->with('success', 'Dokumen RPS / Silabus / Modul Pertemuan 1–13 berhasil diunggah!');
    }

    public function dosenSubmitLaporan(Request $request, Schedule $schedule)
    {
        $request->validate([
            'jumlah_pertemuan_terlaksana' => 'required|integer|min:1|max:16',
            'status_presensi_pijar'       => 'required|string|max:255',
            'tautan_evaluasi'             => 'nullable|string|max:255',
            'link_gdrive_dokumentasi'     => 'required|url|max:500',
        ]);

        $schedule->update([
            'jumlah_pertemuan_terlaksana' => $request->jumlah_pertemuan_terlaksana,
            'status_presensi_pijar'       => $request->status_presensi_pijar,
            'tautan_evaluasi'             => $request->tautan_evaluasi,
            'link_gdrive_dokumentasi'     => $request->link_gdrive_dokumentasi,
            'status_eksternal'            => 'completed',
            'status_bap'                  => 'Laporan Selesai Mengajar Terkirim',
        ]);

        return back()->with('success', 'Laporan Selesai Mengajar & Tautan Google Drive Dokumentasi Foto berhasil dikirim!');
    }
}