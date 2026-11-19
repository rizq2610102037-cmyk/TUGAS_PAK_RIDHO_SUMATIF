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
    // 2. PORTAL KAPRODI (PENGAJUAN JADWAL & PRAKTISI PER JURUSAN)
    // =========================================================
    public function kaprodiDashboard(Request $request)
    {
        $user = Auth::user();

        if ($user->must_change_password) {
            return redirect()->route('password.force');
        }

        // Ambil Mata Kuliah HANYA milik jurusan Kaprodi yang sedang login
        $prodiUser = trim((string) $user->prodi);

        $courses = Course::when($prodiUser !== '', function ($q) use ($prodiUser) {
                $q->where(function ($sub) use ($prodiUser) {
                    $sub->where('prodi', $prodiUser)
                        ->orWhere('prodi', 'like', '%' . $prodiUser . '%');

                    // Padanan Inggris <-> Indonesia (Business Information System <-> Sistem Informasi)
                    if (stripos($prodiUser, 'business information system') !== false || stripos($prodiUser, 'sistem informasi') !== false) {
                        $sub->orWhere('prodi', 'Business Information System')
                            ->orWhere('prodi', 'Sistem Informasi');
                    }
                });
            })
            ->orderBy('semester')
            ->orderBy('nama_mk')
            ->get();

        $rooms = Room::orderBy('kode_ruang')->get();

        $dosens = User::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $schedules = Schedule::with(['course', 'room'])
            ->when($prodiUser !== '', function ($q) use ($prodiUser) {
                $q->whereHas('course', function ($c) use ($prodiUser) {
                    $c->where('prodi', $prodiUser)
                      ->orWhere('prodi', 'like', '%' . $prodiUser . '%');

                    if (stripos($prodiUser, 'business information system') !== false || stripos($prodiUser, 'sistem informasi') !== false) {
                        $c->orWhere('prodi', 'Business Information System')
                          ->orWhere('prodi', 'Sistem Informasi');
                    }
                });
            })
            ->latest()
            ->paginate(10);

        return view('kaprodi.dashboard', compact('user', 'courses', 'rooms', 'dosens', 'schedules'));
    }

    public function kaprodiStorePengajuan(Request $request)
    {
        $user = Auth::user();
        $course = Course::findOrFail($request->course_id);

        // Proteksi tambahan agar Kaprodi tidak bisa mengajukan MK di luar jurusannya
        $prodiUser = trim((string) $user->prodi);
        if ($prodiUser !== '') {
            $isSameProdi = strcasecmp($course->prodi, $prodiUser) === 0
                || stripos($course->prodi, $prodiUser) !== false
                || (
                    (stripos($prodiUser, 'business information system') !== false || stripos($prodiUser, 'sistem informasi') !== false)
                    && (stripos($course->prodi, 'business information system') !== false || stripos($course->prodi, 'sistem informasi') !== false)
                );

            if (!$isSameProdi) {
                return back()->withErrors(['course_id' => 'Mata kuliah tersebut bukan milik Program Studi ' . $prodiUser . '.']);
            }
        }

        $request->validate([
            'course_id'           => 'required|exists:courses,id',
            'room_id'             => 'required|exists:rooms,id',
            'dosen_pengajar'      => 'required|string|max:200',
            'dosen_email'         => 'nullable|email|max:200',
            'praktisi_nama'       => 'nullable|string|max:200',
            'praktisi_email'      => 'nullable|email|max:200',
            'mitra_instansi'      => 'nullable|string|max:200',
            'jabatan_praktisi'    => 'nullable|string|max:200',
            'tahun_ajaran'        => 'required|string|max:30',
            'semester_jenis'      => 'required|in:Ganjil,Genap',
            'semester_angka'      => 'required|integer|min:1|max:8',
            'catatan_pengaju'     => 'nullable|string',
            'dokumen_bahan_ajar'  => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('dokumen_bahan_ajar')) {
            $filePath = $request->file('dokumen_bahan_ajar')->store('rps_bahan_ajar', 'public');
        }

        $isExternal = $request->boolean('is_external') || filled($request->praktisi_nama);

        Schedule::create([
            'course_id'          => $request->course_id,
            'room_id'            => $request->room_id,
            'kelas'              => 'Kelas A',
            'dosen_pengajar'     => $request->dosen_pengajar,
            'dosen_email'        => $request->dosen_email,
            'dosen_email_kampus' => $request->dosen_email,
            'praktisi_nama'      => $request->praktisi_nama,
            'praktisi_email'     => $request->praktisi_email,
            'mitra_instansi'     => $request->mitra_instansi,
            'jabatan_praktisi'   => $request->jabatan_praktisi,
            'tahun_ajaran'       => $request->tahun_ajaran,
            'semester_jenis'     => $request->semester_jenis,
            'semester_angka'     => $request->semester_angka,
            'hari_sesi'          => 'TA ' . $request->tahun_ajaran,
            'waktu_sesi'         => 'Semester ' . $request->semester_jenis,
            'peran_pengaju'      => 'Kaprodi ' . ($user->prodi ?? ''),
            'jenis_program'      => $isExternal ? 'SUAR' : 'PIJAR',
            'is_external'        => $isExternal,
            'status'             => 'pending_approval',
            'status_eksternal'   => 'pending',
            'catatan_pengaju'    => $request->catatan_pengaju,
            'dokumen_bahan_ajar' => $filePath,
        ]);

        return back()->with('success', 'Pengajuan jadwal & praktisi untuk Prodi ' . ($user->prodi ?? '') . ' berhasil dikirim ke Admin Pusat!');
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
                  ->orWhere('dosen_email_kampus', $user->email)
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