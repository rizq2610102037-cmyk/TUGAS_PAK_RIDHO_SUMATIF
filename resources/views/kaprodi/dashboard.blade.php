<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kaprodi - Pengajuan Praktisi Mengajar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Kaprodi &rsaquo; Program Studi {{ $user->prodi ?? 'Universitas Pradita' }}</p>
                <h1 class="text-white text-xl font-extrabold">Pengajuan & Status Praktisi Mengajar (SUAR)</h1>
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
            <span class="text-xs text-white/90">Kaprodi {{ $user->prodi ?? 'Prodi' }}</span>
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

        {{-- FORM PENGAJUAN PRAKTISI BERBASIS DROPDOWN MASTER DATA --}}
        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100">
            <div class="border-b pb-3 mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F57C00]">FORMULIR PENGAJUAN BARU</span>
                <h2 class="text-lg font-extrabold text-[#0A4D29]">Ajukan Praktisi Industri untuk Mata Kuliah</h2>
                <p class="text-xs text-gray-500">Pilih Mata Kuliah dan Dosen Pengampu dari master data, lalu isi identitas praktisi industri serta periode semester (Pertemuan 1–12).</p>
            </div>

            <form action="{{ route('kaprodi.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Mata Kuliah (Master Data)</label>
                    <select name="course_id" required class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->kode_mk }} - {{ $c->nama_mk }} ({{ $c->sks }} SKS)</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-1">Dosen Pengampu (Master Data)</label>
                    <select name="dosen_pengajar" required class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                        <option value="">-- Pilih Dosen Pengampu --</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-1">Email Dosen Pengampu (Untuk Akun Portal)</label>
                    <input type="email" name="dosen_email" placeholder="dosen@pradita.ac.id" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold mb-1">Nama Lengkap Praktisi Industri</label>
                    <input type="text" name="praktisi_nama" required placeholder="Contoh: Ir. Andi Pratama, M.T." class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold mb-1">Email Praktisi (Untuk Akun Portal Otomatis)</label>
                    <input type="email" name="praktisi_email" required placeholder="andi.pratama@perusahaan.com" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold mb-1">Instansi Mitra & Jabatan</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="mitra_instansi" required placeholder="PT Telkom" class="w-full border border-gray-300 rounded-xl px-2.5 py-2">
                        <input type="text" name="jabatan_praktisi" required placeholder="VP Tech" class="w-full border border-gray-300 rounded-xl px-2.5 py-2">
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1">Tahun Ajaran</label>
                    <select name="tahun_ajaran" required class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                        <option value="2026-2027">2026-2027</option>
                        <option value="2027-2028">2027-2028</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-1">Semester (Ganjil / Genap & Ke-)</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="semester_jenis" required class="w-full border border-gray-300 rounded-xl px-2.5 py-2 bg-gray-50">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        <input type="number" name="semester_angka" min="1" max="8" value="5" required class="w-full border border-gray-300 rounded-xl px-2.5 py-2">
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1">Dokumen RPS Awal (PDF/DOCX)</label>
                    <input type="file" name="dokumen_bahan_ajar" class="w-full border border-gray-300 rounded-xl px-2.5 py-1.5 bg-gray-50">
                </div>

                <div class="md:col-span-3 flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-extrabold cursor-pointer">
                        + Kirim Pengajuan Praktisi ke Admin Pusat
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL RIWAYAT PENGAJUAN & INFO AKUN OTOMATIS SETELAH ACC --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-extrabold text-base text-gray-900">Status Pengajuan & Kredensial Akun Otomatis</h3>
                <p class="text-xs text-gray-500">Setelah pengajuan di-ACC oleh Admin Pusat, informasi akun login Dosen & Praktisi akan tampil di sini.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase border-b">
                            <th class="py-3 px-4">MATA KULIAH</th>
                            <th class="py-3 px-4">DOSEN & PRAKTISI</th>
                            <th class="py-3 px-4">PERIODE</th>
                            <th class="py-3 px-4 text-center">STATUS VERIFIKASI</th>
                            <th class="py-3 px-4">KREDENSIAL AKUN OTOMATIS (SETELAH ACC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($schedules as $s)
                            <tr>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-gray-900">{{ $s->course->nama_mk }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $s->course->kode_mk }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-semibold">Dosen: {{ $s->dosen_pengajar }}</p>
                                    <p class="text-[11px] text-[#0A4D29] font-bold">Praktisi: {{ $s->praktisi_nama ?? '-' }} ({{ $s->mitra_instansi ?? '-' }})</p>
                                </td>
                                <td class="py-3.5 px-4 font-semibold">
                                    TA {{ $s->tahun_ajaran ?? '2026-2027' }} ({{ $s->semester_jenis ?? 'Ganjil' }})
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($s->status === 'approved')
                                        <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">✓ ACC / Disetujui</span>
                                    @elseif ($s->status === 'rejected')
                                        <span class="bg-[#FADBD8] text-[#C0392B] font-bold px-3 py-1 rounded-full text-[11px]">✕ Ditolak</span>
                                    @else
                                        <span class="bg-[#FFF0E0] text-[#F57C00] font-bold px-3 py-1 rounded-full text-[11px]">⏳ Menunggu ACC</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($s->status === 'approved' && $s->akun_generated_info)
                                        <div class="bg-[#FAFCEB] border border-[#0A4D29]/20 rounded-lg p-2 text-[11px] text-[#0A4D29] font-semibold">
                                            {{ $s->akun_generated_info }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Terbit otomatis saat disetujui</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">Belum ada pengajuan praktisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>