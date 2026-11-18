<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring & Pelaporan Praktisi Eksternal - Admin PIJAR & SUAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin Terpadu &rsaquo; Pelaksanaan & Pelaporan SUAR</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Monitoring & Pelaporan Jadwal Eksternal</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('pijar.jadwal-eksternal') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari praktisi, instansi mitra, atau mata kuliah..." 
                        class="w-full pl-9 pr-4 py-1.5 rounded-full bg-[#E8F5E9] text-xs text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
                    >
                </form>
            </div>
        </div>

        <div class="w-full md:w-1/4 bg-[#F57C00] px-6 py-3 flex flex-col justify-center items-end text-right text-white">
            <span class="font-bold text-base leading-tight">{{ Auth::user()->name }}</span>
            <span class="text-xs text-white/90">{{ Auth::user()->email }}</span>
        </div>
    </header>

    {{-- MAIN CONTAINER --}}
    <div class="flex-1 p-4 md:p-6 max-w-[1440px] w-full mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-[#D5F5E3] border border-[#0A4D29] text-[#0A4D29] px-4 py-3 rounded-xl text-xs font-bold flex justify-between items-center shadow-sm">
                <span>✅ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-sm font-extrabold cursor-pointer">✕</button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

            {{-- SIDEBAR TERPADU --}}
            @include('layouts.sidebar-admin')

            {{-- KONTEN UTAMA KANAN --}}
            <div class="flex-1 min-w-0 w-full space-y-5">

                {{-- HEADER HALAMAN + TOMBOL EKSPOR & SINKRONISASI --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Monitoring & Pelaporan Pasca-Pelaksanaan</h2>
                        <p class="text-xs text-gray-600 mt-1">Kelola progres pertemuan praktisi (1–12), verifikasi absensi, evaluasi, dan tautan <strong>Google Drive dokumentasi foto</strong> sebagai dasar pencairan honorarium.</p>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('pijar.rekap-pengajaran.export') }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-bold text-xs shadow-xs">
                            ⬇ Export CSV Laporan
                        </a>
                        <a href="{{ route('pijar.jadwal-eksternal') }}" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs">
                            ↻ Sinkronisasi Mitra
                        </a>
                    </div>
                </div>

                {{-- 5 KARTU METRIK ATAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase">TOTAL JADWAL</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalEksternal }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-gray-100 text-gray-600 text-xs">📅</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="font-bold text-gray-700">100%</span>
                            <span class="text-gray-500">Program SUAR terdaftar</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border-b-4 border-[#0A4D29] flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-[#0A4D29] uppercase">READY TO TEACH</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $readyToTeach }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-[#D5F5E3] text-[#0A4D29] text-xs">✔</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2 py-0.5 rounded">Siap Ajar</span>
                            <span class="text-gray-500">RPS & akun aktif</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border-b-4 border-[#F57C00] flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-[#F57C00] uppercase">SEDANG BERLANGSUNG</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $sedangBerlangsung }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-[#FFF0E0] text-[#F57C00] text-xs">▶</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="bg-[#FFF0E0] text-[#F57C00] font-bold px-2 py-0.5 rounded">Berjalan</span>
                            <span class="text-gray-500">Pertemuan 1–12</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-500 uppercase">SELESAI MENGAJAR</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $sudahTerlaksana }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-gray-100 text-gray-700 text-xs">☑</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="font-bold text-gray-800">{{ $persenTerlaksana }}%</span>
                            <span class="text-gray-500">Siap pencairan honor</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-400 uppercase">BELUM TERLAKSANA</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $belumTerlaksana }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-gray-100 text-gray-600 text-xs">🕒</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="bg-gray-100 text-gray-600 font-bold px-2 py-0.5 rounded">Pending</span>
                            <span class="text-gray-500">Menunggu jadwal</span>
                        </div>
                    </div>
                </div>

                {{-- FILTER BAR --}}
                <form action="{{ route('pijar.jadwal-eksternal') }}" method="GET" class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-wrap items-center gap-3 text-xs">
                    <select name="prodi" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Program Studi</option>
                        @foreach($listProdi as $p)
                            <option value="{{ $p }}" {{ $prodi === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>

                    <select name="status_eksternal" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Status Pelaksanaan</option>
                        <option value="ready_to_teach" {{ $status === 'ready_to_teach' ? 'selected' : '' }}>Ready to Teach</option>
                        <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai Mengajar (Completed)</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Belum Terlaksana</option>
                    </select>

                    <select name="mitra" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Mitra Industri</option>
                        @foreach($listMitra as $m)
                            <option value="{{ $m }}" {{ $mitra === $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('pijar.jadwal-eksternal') }}" class="ml-auto px-3.5 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 font-bold text-gray-600">
                        ↻ Reset Filter
                    </a>
                </form>

                {{-- MASTER-DETAIL GRID: TABEL KIRI + PANEL PELAPORAN PASCA-PELAKSANAAN KANAN --}}
                @php $firstItem = $externalSchedules->first(); @endphp
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

                    {{-- KOLOM KIRI: DAFTAR SESI PERKULIAHAN EKSTERNAL (7 Kolom) --}}
                    <div class="xl:col-span-7 bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-extrabold text-base text-gray-900">Daftar Kelas Praktisi (SUAR)</h3>
                                <span class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $externalSchedules->count() }} dari {{ $totalEksternal }} Data
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Klik baris untuk isi pelaporan &rarr;</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                        <th class="py-3 px-4 w-10">NO</th>
                                        <th class="py-3 px-4">MATA KULIAH & SEMESTER</th>
                                        <th class="py-3 px-4">PRAKTISI & MITRA</th>
                                        <th class="py-3 px-4 text-center">PROGRES & DOKUMENTASI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-xs">
                                    @forelse ($externalSchedules as $index => $item)
                                        <tr 
                                            onclick="selectExternalSession(this)"
                                            data-id="{{ $item->id }}"
                                            data-prodi="{{ $item->course->prodi }}"
                                            data-kodemk="{{ $item->course->kode_mk }}"
                                            data-namamk="{{ $item->course->nama_mk }}"
                                            data-fokus="{{ $item->catatan_pengaju ?? 'Perkuliahan praktisi industri terintegrasi kurikulum.' }}"
                                            data-dosen="{{ $item->praktisi_nama ?: $item->dosen_pengajar }}"
                                            data-mitra="{{ $item->mitra_instansi ?? 'Mitra Industri Pradita' }}"
                                            data-jabatan="{{ $item->jabatan_praktisi ?? 'Practitioner & Guest Lecturer' }}"
                                            data-tahunajaran="{{ $item->tahun_ajaran ?? '2026-2027' }}"
                                            data-semester="{{ ($item->semester_jenis ?? 'Ganjil') . ' (Sem. ' . ($item->semester_angka ?? 1) . ')' }}"
                                            data-ruang="{{ $item->room->kode_ruang }}"
                                            data-gedung="{{ $item->room->nama_ruang }}"
                                            data-honor="{{ $item->status_honorarium ?? 'Menunggu Laporan Selesai' }}"
                                            data-bap="{{ $item->status_bap ?? 'Belum Diverifikasi' }}"
                                            data-presensi="{{ $item->status_presensi_pijar ?? '' }}"
                                            data-evaluasi="{{ $item->tautan_evaluasi ?? '' }}"
                                            data-gdrive="{{ $item->link_gdrive_dokumentasi ?? '' }}"
                                            data-pertemuan="{{ $item->jumlah_pertemuan_terlaksana ?? 0 }}"
                                            data-status="{{ $item->status_eksternal ?? 'ready_to_teach' }}"
                                            class="cursor-pointer transition hover:bg-[#FAFCEB] {{ $index === 0 ? 'bg-[#E8F5E9]/70 border-l-4 border-[#0A4D29]' : '' }}"
                                        >
                                            <td class="py-4 px-4 font-bold text-gray-500">{{ $externalSchedules->firstItem() + $index }}</td>
                                            <td class="py-4 px-4">
                                                <p class="font-bold text-gray-900 leading-snug">{{ $item->course->nama_mk }}</p>
                                                <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[11px] text-gray-500">
                                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded font-mono font-bold text-gray-700">{{ $item->course->kode_mk }}</span>
                                                    <span>• TA {{ $item->tahun_ajaran ?? '2026-2027' }}</span>
                                                    <span class="text-[#0A4D29] font-semibold">• {{ $item->course->prodi }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <p class="font-bold text-gray-900">{{ $item->praktisi_nama ?: $item->dosen_pengajar }}</p>
                                                <p class="text-[11px] text-gray-500">{{ $item->mitra_instansi ?? 'Praktisi Industri' }}</p>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <span class="inline-block font-bold text-[#0A4D29] bg-[#D5F5E3] px-2.5 py-0.5 rounded-full text-[10px]">
                                                    {{ $item->jumlah_pertemuan_terlaksana ?? 0 }} / 12 Pertemuan
                                                </span>
                                                @if($item->link_gdrive_dokumentasi)
                                                    <span class="block text-[10px] text-[#0A4D29] font-semibold mt-1">✓ G-Drive Foto Terlampir</span>
                                                @else
                                                    <span class="block text-[10px] text-[#F57C00] mt-1">Belum ada link foto</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-10 text-center text-gray-500">Belum ada jadwal pihak eksternal.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                            {{ $externalSchedules->links() }}
                        </div>
                    </div>

                    {{-- KOLOM KANAN: MODUL PELAPORAN PASCA-PELAKSANAAN (5 Kolom) --}}
                    <div class="xl:col-span-5 bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between border-b pb-3">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F57C00]">MODUL PASCA-PELAKSANAAN</span>
                                <h3 class="font-extrabold text-sm text-[#0A4D29]">Pelaporan Selesai Mengajar & Dokumentasi</h3>
                            </div>
                            <span id="detail_status_badge" class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-3 py-0.5 rounded-full">
                                {{ $firstItem ? ucwords(str_replace('_', ' ', $firstItem->status_eksternal ?? 'ready_to_teach')) : 'Ready to Teach' }}
                            </span>
                        </div>

                        @if($firstItem)
                            <div>
                                <p id="detail_prodi_kode" class="text-[11px] font-bold text-[#F57C00]">
                                    {{ $firstItem->course->prodi }} • {{ $firstItem->course->kode_mk }}
                                </p>
                                <h4 id="detail_nama_mk" class="text-lg font-extrabold text-gray-900 leading-snug mt-0.5">
                                    {{ $firstItem->course->nama_mk }}
                                </h4>
                            </div>

                            {{-- Card Profil Praktisi --}}
                            <div class="bg-[#FAFCEB] border border-[#0A4D29]/15 rounded-xl p-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#0A4D29] text-white font-extrabold flex items-center justify-center text-sm shrink-0">
                                    {{ strtoupper(substr($firstItem->praktisi_nama ?: $firstItem->dosen_pengajar, 0, 2)) }}
                                </div>
                                <div class="text-xs">
                                    <p id="detail_dosen" class="font-extrabold text-gray-900">{{ $firstItem->praktisi_nama ?: $firstItem->dosen_pengajar }}</p>
                                    <p id="detail_mitra" class="font-bold text-[#0A4D29]">{{ $firstItem->mitra_instansi ?? 'Mitra Industri' }}</p>
                                </div>
                            </div>

                            {{-- Grid Periode Semester & Honorarium --}}
                            <div class="grid grid-cols-2 gap-2.5 text-xs">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">PERIODE SEMESTER</p>
                                    <p id="detail_tahun_ajaran" class="font-extrabold text-gray-900 mt-1">TA {{ $firstItem->tahun_ajaran ?? '2026-2027' }}</p>
                                    <p id="detail_semester" class="text-[11px] text-gray-600">{{ ($firstItem->semester_jenis ?? 'Ganjil') . ' (Sem. ' . ($firstItem->semester_angka ?? 1) . ')' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">STATUS HONORARIUM</p>
                                    <p id="detail_bap" class="font-bold text-[#0A4D29] mt-1">{{ $firstItem->status_bap ?? 'Menunggu Laporan Selesai' }}</p>
                                </div>
                            </div>

                            {{-- Form Pelaporan Pasca-Pelaksanaan (Absensi, Evaluasi, Link Google Drive Foto) --}}
                            <form id="formUpdateEksternal" action="{{ route('pijar.jadwal-eksternal.status', $firstItem->id) }}" method="POST" class="space-y-3 text-xs pt-2 border-t">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-1">Pertemuan Terlaksana (1–12)</label>
                                        <input type="number" name="jumlah_pertemuan_terlaksana" id="input_pertemuan" min="0" max="16" value="{{ $firstItem->jumlah_pertemuan_terlaksana ?? 0 }}" class="w-full border border-gray-300 rounded-xl px-3 py-2 font-bold">
                                    </div>
                                    <div>
                                        <label class="block font-bold text-gray-700 mb-1">Status Pelaksanaan</label>
                                        <select name="status_eksternal" id="select_status_eksternal" class="w-full border border-gray-300 rounded-xl px-2.5 py-2 font-bold">
                                            <option value="ready_to_teach" {{ $firstItem->status_eksternal === 'ready_to_teach' ? 'selected' : '' }}>Ready to Teach</option>
                                            <option value="ongoing" {{ $firstItem->status_eksternal === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                            <option value="completed" {{ $firstItem->status_eksternal === 'completed' ? 'selected' : '' }}>Selesai Mengajar</option>
                                            <option value="pending" {{ $firstItem->status_eksternal === 'pending' ? 'selected' : '' }}>Belum Terlaksana</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block font-bold text-gray-700 mb-1">Rekap Absensi / Presensi Kelas</label>
                                    <input type="text" name="status_presensi_pijar" id="input_presensi" value="{{ $firstItem->status_presensi_pijar ?? '' }}" placeholder="Contoh: Hadir 38/40 Mahasiswa (BAP Terisi)" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                                </div>

                                <div>
                                    <label class="block font-bold text-gray-700 mb-1">Catatan / Tautan Evaluasi Pengajaran</label>
                                    <input type="text" name="tautan_evaluasi" id="input_evaluasi" value="{{ $firstItem->tautan_evaluasi ?? '' }}" placeholder="Skor evaluasi / link form evaluasi..." class="w-full border border-gray-300 rounded-xl px-3 py-2">
                                </div>

                                <div>
                                    <label class="block font-bold text-[#0A4D29] mb-1">📎 Tautan Google Drive Dokumentasi Foto (Wajib untuk Honor)</label>
                                    <input type="url" name="link_gdrive_dokumentasi" id="input_gdrive" value="{{ $firstItem->link_gdrive_dokumentasi ?? '' }}" placeholder="https://drive.google.com/drive/folders/..." class="w-full border border-[#0A4D29]/40 bg-[#FAFCEB] rounded-xl px-3 py-2">
                                    @if($firstItem->link_gdrive_dokumentasi)
                                        <a id="preview_gdrive_link" href="{{ $firstItem->link_gdrive_dokumentasi }}" target="_blank" class="inline-block text-[11px] text-[#F57C00] font-bold underline mt-1">Buka Folder Dokumentasi Google Drive &rarr;</a>
                                    @else
                                        <a id="preview_gdrive_link" href="#" target="_blank" class="hidden text-[11px] text-[#F57C00] font-bold underline mt-1">Buka Folder Dokumentasi Google Drive &rarr;</a>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 pt-2">
                                    <button type="submit" class="w-full py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs cursor-pointer">
                                        💾 Simpan Laporan Pasca-Pelaksanaan
                                    </button>
                                    <button type="submit" onclick="document.getElementById('select_status_eksternal').value='completed'" class="w-full py-2.5 rounded-xl bg-[#F57C00] hover:bg-[#e07000] text-white font-extrabold text-xs transition cursor-pointer shadow-xs">
                                        ✓ Tandai Selesai Mengajar (Syarat Pencairan Honor)
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        function selectExternalSession(row) {
            document.querySelectorAll('tbody tr').forEach(tr => {
                tr.classList.remove('bg-[#E8F5E9]/70', 'border-l-4', 'border-[#0A4D29]');
            });
            row.classList.add('bg-[#E8F5E9]/70', 'border-l-4', 'border-[#0A4D29]');

            const d = row.dataset;
            document.getElementById('formUpdateEksternal').action = '/pijar/jadwal-eksternal/' + d.id + '/status';
            document.getElementById('detail_prodi_kode').textContent = d.prodi + ' • ' + d.kodemk;
            document.getElementById('detail_nama_mk').textContent = d.namamk;
            document.getElementById('detail_dosen').textContent = d.dosen;
            document.getElementById('detail_mitra').textContent = d.mitra;
            document.getElementById('detail_tahun_ajaran').textContent = 'TA ' + d.tahunajaran;
            document.getElementById('detail_semester').textContent = d.semester;
            document.getElementById('detail_bap').textContent = d.bap;
            document.getElementById('select_status_eksternal').value = d.status;
            document.getElementById('input_pertemuan').value = d.pertemuan || 0;
            document.getElementById('input_presensi').value = d.presensi || '';
            document.getElementById('input_evaluasi').value = d.evaluasi || '';
            document.getElementById('input_gdrive').value = d.gdrive || '';

            const gdriveLink = document.getElementById('preview_gdrive_link');
            if (d.gdrive) {
                gdriveLink.href = d.gdrive;
                gdriveLink.classList.remove('hidden');
            } else {
                gdriveLink.classList.add('hidden');
            }

            const statusLabels = {
                'ready_to_teach': 'Ready to Teach',
                'ongoing': 'Sedang Berlangsung',
                'completed': 'Selesai Mengajar',
                'pending': 'Belum Terlaksana'
            };
            document.getElementById('detail_status_badge').textContent = statusLabels[d.status] || 'Ready to Teach';
        }
    </script>
</body>
</html>