<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Dosen & Praktisi - Perangkat Ajar & Pelaporan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Akademik & Praktisi &rsaquo; Perangkat Ajar & Pelaporan</p>
                <h1 class="text-white text-xl font-extrabold">Unggah RPS, Silabus, Modul 1–13 & Laporan Akhir</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
        <div class="w-full md:w-1/4 bg-[#F57C00] px-6 py-3 flex flex-col justify-center items-end text-right text-white">
            <span class="font-bold text-base leading-tight">{{ $user->name }}</span>
            <span class="text-xs text-white/90">{{ ucfirst($user->role) }} • {{ $user->email }}</span>
        </div>
    </header>

    <main class="flex-1 p-4 md:p-6 max-w-6xl w-full mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-[#D5F5E3] border border-[#0A4D29] text-[#0A4D29] px-4 py-3 rounded-xl text-xs font-bold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-[#FADBD8] border border-[#C0392B] text-[#C0392B] px-4 py-3 rounded-xl text-xs font-bold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        @forelse ($schedules as $s)
            <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100 space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b pb-4">
                    <div>
                        <span class="bg-[#E8F5E9] text-[#0A4D29] font-extrabold px-2.5 py-0.5 rounded text-[11px]">
                            {{ $s->course->kode_mk }} • TA {{ $s->tahun_ajaran ?? '2026-2027' }} ({{ $s->semester_jenis ?? 'Ganjil' }})
                        </span>
                        <h2 class="text-xl font-extrabold text-gray-900 mt-1">{{ $s->course->nama_mk }}</h2>
                        <p class="text-xs text-gray-500">Dosen: <strong>{{ $s->dosen_pengajar }}</strong> • Praktisi: <strong>{{ $s->praktisi_nama ?? '-' }}</strong> ({{ $s->mitra_instansi ?? '-' }})</p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold {{ $s->status_eksternal === 'completed' ? 'bg-[#D5F5E3] text-[#0A4D29]' : 'bg-[#FFF0E0] text-[#F57C00]' }}">
                        Status: {{ ucwords(str_replace('_', ' ', $s->status_eksternal ?? 'ready_to_teach')) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 text-xs">
                    {{-- BAGIAN 1: UNGGAH PERANGKAT PEMBELAJARAN (RPS, SILABUS, MODUL 1-13) --}}
                    <form action="{{ route('dosen.upload.perangkat', $s->id) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-xl p-4 border space-y-3">
                        @csrf
                        <h3 class="font-extrabold text-sm text-[#0A4D29]">1. Unggah Perangkat Pembelajaran (Pertemuan 1–13)</h3>

                        <div>
                            <label class="block font-bold mb-1">Dokumen RPS (PDF/DOCX)</label>
                            <input type="file" name="file_rps" class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white">
                            @if($s->file_rps)
                                <a href="{{ asset('storage/' . $s->file_rps) }}" target="_blank" class="text-[#0A4D29] font-bold underline mt-1 inline-block">✓ RPS Terunggah (Lihat)</a>
                            @endif
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Dokumen Silabus (PDF/DOCX)</label>
                            <input type="file" name="file_silabus" class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white">
                            @if($s->file_silabus)
                                <a href="{{ asset('storage/' . $s->file_silabus) }}" target="_blank" class="text-[#0A4D29] font-bold underline mt-1 inline-block">✓ Silabus Terunggah (Lihat)</a>
                            @endif
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Modul Pertemuan 1–13 (PDF/PPTX/ZIP)</label>
                            <input type="file" name="file_modul_1_13" class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white">
                            @if($s->file_modul_1_13)
                                <a href="{{ asset('storage/' . $s->file_modul_1_13) }}" target="_blank" class="text-[#0A4D29] font-bold underline mt-1 inline-block">✓ Modul 1–13 Terunggah (Lihat)</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold cursor-pointer">
                            ⬆ Simpan & Unggah Perangkat Ajar
                        </button>
                    </form>

                    {{-- BAGIAN 2: PELAPORAN PASCA-PELAKSANAAN & LINK GOOGLE DRIVE DOKUMENTASI FOTO --}}
                    <form action="{{ route('dosen.submit.laporan', $s->id) }}" method="POST" class="bg-[#FAFCEB] rounded-xl p-4 border border-[#0A4D29]/20 space-y-3">
                        @csrf
                        <h3 class="font-extrabold text-sm text-[#F57C00]">2. Pelaporan Selesai Mengajar & Dokumentasi Foto</h3>

                        <div>
                            <label class="block font-bold mb-1">Jumlah Pertemuan Terlaksana (1–12)</label>
                            <input type="number" name="jumlah_pertemuan_terlaksana" min="1" max="16" value="{{ $s->jumlah_pertemuan_terlaksana ?: 12 }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white font-bold">
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Ringkasan Rekap Absensi Kelas</label>
                            <input type="text" name="status_presensi_pijar" value="{{ $s->status_presensi_pijar }}" required placeholder="Contoh: Hadir 36/38 Mahasiswa (12 Pertemuan Tuntas)" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block font-bold mb-1">Catatan / Tautan Evaluasi</label>
                            <input type="text" name="tautan_evaluasi" value="{{ $s->tautan_evaluasi }}" placeholder="Skor evaluasi / link evaluasi..." class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        </div>

                        <div>
                            <label class="block font-bold text-[#0A4D29] mb-1">📎 Tautan Google Drive Dokumentasi Foto (Wajib)</label>
                            <input type="url" name="link_gdrive_dokumentasi" value="{{ $s->link_gdrive_dokumentasi }}" required placeholder="https://drive.google.com/drive/folders/..." class="w-full border border-[#0A4D29] rounded-lg px-3 py-2 bg-white">
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl bg-[#F57C00] hover:bg-[#e07000] text-white font-extrabold cursor-pointer">
                            ✓ Kirim Laporan Selesai Mengajar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-10 text-center border border-gray-100 text-xs text-gray-500">
                Belum ada mata kuliah praktisi yang terhubung dengan akun <strong>{{ $user->email }}</strong>.
            </div>
        @endforelse

    </main>
</body>
</html>