<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Jadwal Pihak Eksternal - Admin PIJAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin &rsaquo; Akademik &rsaquo; Kemitraan Industri</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Monitoring Jadwal Pihak Eksternal</h1>
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

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- SIDEBAR MENU --}}
            <aside class="w-full lg:w-64 bg-[#0A4D29] rounded-2xl p-4 text-white flex flex-col justify-between lg:min-h-[750px] shrink-0 shadow-md">
                <nav class="space-y-2">
                    <a href="{{ route('pijar.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('pijar.manajemen-jadwal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Manajemen Jadwal
                    </a>

                    {{-- Menu Aktif: Jadwal Pihak Eksternal --}}
                    <a href="{{ route('pijar.jadwal-eksternal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Jadwal Pihak Eksternal
                    </a>

                    <a href="{{ route('pijar.manajemen-akun') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Manajemen Akun
                    </a>

                    <a href="{{ route('pijar.rekap-pengajaran') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Rekap Pengajaran
                    </a>
                </nav>

                <div class="pt-8 mt-8 border-t border-white/15 space-y-2">
                    <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan
                    </a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-[#B7F4D1] hover:bg-red-500/20 hover:text-white transition text-sm cursor-pointer text-left">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            {{-- KONTEN UTAMA KANAN --}}
            <div class="flex-1 w-full space-y-5">

                {{-- HEADER HALAMAN + TOMBOL EKSPOR & SINKRONISASI --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Monitoring Jadwal Pihak Eksternal</h2>
                        <p class="text-xs text-gray-600 mt-1">Basis data pemantauan ketersediaan jadwal ajar siap ambil (<strong>Ready to Teach</strong>) dan status pelaksanaan pengajar dari mitra luar/praktisi industri.</p>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-bold text-xs shadow-xs cursor-pointer">
                            ⬇ Export Data Jadwal
                        </button>
                        <a href="{{ route('pijar.jadwal-eksternal') }}" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs">
                            ↻ Sinkronisasi Mitra Eksternal
                        </a>
                    </div>
                </div>

                {{-- 5 KARTU METRIK ATAS (PERSIS LAYOUT FOTO) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
                    {{-- 1. Total Jadwal --}}
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
                            <span class="text-gray-500">Sesi Terdaftar Semester Ini</span>
                        </div>
                    </div>

                    {{-- 2. Ready to Teach --}}
                    <div class="bg-white rounded-2xl p-4 shadow-xs border-b-4 border-[#0A4D29] flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-[#0A4D29] uppercase">READY TO TEACH</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $readyToTeach }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-[#D5F5E3] text-[#0A4D29] text-xs">✔</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2 py-0.5 rounded">Tersedia</span>
                            <span class="text-gray-500">Alokasi praktisi dibuka</span>
                        </div>
                    </div>

                    {{-- 3. Sedang Berlangsung --}}
                    <div class="bg-white rounded-2xl p-4 shadow-xs border-b-4 border-[#F57C00] flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-[#F57C00] uppercase">SEDANG BERLANGSUNG</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $sedangBerlangsung }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-[#FFF0E0] text-[#F57C00] text-xs">▶</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="bg-[#FFF0E0] text-[#F57C00] font-bold px-2 py-0.5 rounded">Aktif</span>
                            <span class="text-gray-500">Kelas tatap muka / daring</span>
                        </div>
                    </div>

                    {{-- 4. Sudah Terlaksana --}}
                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-[10px] font-extrabold text-gray-500 uppercase">SUDAH TERLAKSANA</p>
                                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $sudahTerlaksana }}</p>
                            </div>
                            <span class="p-2 rounded-lg bg-gray-100 text-gray-700 text-xs">☑</span>
                        </div>
                        <div class="flex items-center gap-2 mt-3 text-[11px]">
                            <span class="font-bold text-gray-800">{{ $persenTerlaksana }}%</span>
                            <span class="text-gray-500">Tuntas & terverifikasi BAP</span>
                        </div>
                    </div>

                    {{-- 5. Belum Terlaksana --}}
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
                            <span class="text-gray-500">Menunggu konfirmasi</span>
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
                        <option value="">Semua Status Pengajaran</option>
                        <option value="ready_to_teach" {{ $status === 'ready_to_teach' ? 'selected' : '' }}>Ready to Teach</option>
                        <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Sudah Terlaksana</option>
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

                {{-- MASTER-DETAIL GRID: TABEL KIRI + PANEL DETAIL KANAN --}}
                @php $firstItem = $externalSchedules->first(); @endphp
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

                    {{-- KOLOM KIRI: DAFTAR SESI PERKULIAHAN EKSTERNAL (8 Kolom) --}}
                    <div class="xl:col-span-8 bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-extrabold text-base text-gray-900">Daftar Sesi Perkuliahan Eksternal</h3>
                                <span class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                    {{ $externalSchedules->count() }} dari {{ $totalEksternal }} Data
                                </span>
                            </div>
                            <span class="text-xs text-gray-500">Klik baris untuk lihat detail &rarr;</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                        <th class="py-3 px-4 w-10">NO</th>
                                        <th class="py-3 px-4">NAMA KEGIATAN / MATA KULIAH</th>
                                        <th class="py-3 px-4">PROGRAM STUDI</th>
                                        <th class="py-3 px-4">PIHAK EKSTERNAL / MITRA</th>
                                        <th class="py-3 px-4">JADWAL SESI</th>
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
                                            data-dosen="{{ $item->dosen_pengajar }}"
                                            data-mitra="{{ $item->mitra_instansi ?? 'Mitra Industri Pradita' }}"
                                            data-jabatan="{{ $item->jabatan_praktisi ?? 'Practitioner & Guest Lecturer' }}"
                                            data-harisesi="{{ $item->hari_sesi }}"
                                            data-waktusesi="{{ $item->waktu_sesi }}"
                                            data-ruang="{{ $item->room->kode_ruang }}"
                                            data-gedung="{{ $item->room->nama_ruang }}"
                                            data-honor="{{ $item->status_honorarium }}"
                                            data-bap="{{ $item->status_bap }}"
                                            data-pic="{{ $item->pic_akademik ?? 'Koordinator Akademik Prodi' }}"
                                            data-kontak="{{ $item->pic_kontak ?? '+62 812-0000-0000' }}"
                                            data-zoom="{{ $item->tautan_zoom ?? 'zoom.us/j/pradita-class' }}"
                                            data-status="{{ $item->status_eksternal }}"
                                            class="cursor-pointer transition hover:bg-[#FAFCEB] {{ $index === 0 ? 'bg-[#E8F5E9]/70 border-l-4 border-[#0A4D29]' : '' }}"
                                        >
                                            <td class="py-4 px-4 font-bold text-gray-500">{{ $externalSchedules->firstItem() + $index }}</td>
                                            <td class="py-4 px-4">
                                                <p class="font-bold text-gray-900 leading-snug">{{ $item->course->nama_mk }}</p>
                                                <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[11px] text-gray-500">
                                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded font-mono font-bold text-gray-700">{{ $item->course->kode_mk }}</span>
                                                    <span>• {{ $item->course->sks }} SKS</span>
                                                    <span class="text-[#0A4D29] font-semibold">• {{ $item->room->kode_ruang }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 font-semibold text-gray-700">{{ $item->course->prodi }}</td>
                                            <td class="py-4 px-4">
                                                <p class="font-bold text-gray-900">{{ $item->dosen_pengajar }}</p>
                                                <p class="text-[11px] text-gray-500">{{ $item->mitra_instansi ?? 'Praktisi Industri' }}</p>
                                            </td>
                                            <td class="py-4 px-4">
                                                <p class="font-bold text-[#0A4D29]">{{ $item->hari_sesi }}</p>
                                                <p class="text-[11px] text-gray-500">{{ $item->waktu_sesi }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-10 text-center text-gray-500">Belum ada jadwal pihak eksternal.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                            {{ $externalSchedules->links() }}
                        </div>
                    </div>

                    {{-- KOLOM KANAN: DETAIL SESI TERPILIH (4 Kolom) --}}
                    <div class="xl:col-span-4 bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="font-extrabold text-sm text-[#0A4D29] flex items-center gap-1.5">
                                📋 Detail Sesi Terpilih
                            </span>
                            <span id="detail_status_badge" class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-3 py-0.5 rounded-full">
                                {{ $firstItem ? ucwords(str_replace('_', ' ', $firstItem->status_eksternal)) : 'Ready to Teach' }}
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
                                <p id="detail_fokus" class="text-xs text-gray-500 mt-1.5 leading-relaxed">
                                    {{ $firstItem->catatan_pengaju ?? 'Perkuliahan praktisi industri terintegrasi kurikulum.' }}
                                </p>
                            </div>

                            {{-- Card Profil Praktisi --}}
                            <div class="bg-[#FAFCEB] border border-[#0A4D29]/15 rounded-xl p-3 flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-[#0A4D29] text-white font-extrabold flex items-center justify-center text-sm shrink-0">
                                    {{ strtoupper(substr($firstItem->dosen_pengajar, 0, 2)) }}
                                </div>
                                <div class="text-xs">
                                    <p id="detail_dosen" class="font-extrabold text-gray-900">{{ $firstItem->dosen_pengajar }}</p>
                                    <p id="detail_mitra" class="font-bold text-[#0A4D29]">{{ $firstItem->mitra_instansi ?? 'Mitra Industri' }}</p>
                                    <p id="detail_jabatan" class="text-[11px] text-gray-500">{{ $firstItem->jabatan_praktisi ?? 'Guest Lecturer' }}</p>
                                </div>
                            </div>

                            {{-- Grid 2x2 Info Jadwal, Lokasi, Honorarium, BAP --}}
                            <div class="grid grid-cols-2 gap-2.5 text-xs">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">JADWAL & WAKTU</p>
                                    <p id="detail_hari" class="font-extrabold text-gray-900 mt-1">{{ $firstItem->hari_sesi }}</p>
                                    <p id="detail_waktu" class="text-[11px] text-gray-600">{{ $firstItem->waktu_sesi }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">LOKASI / TAUTAN</p>
                                    <p id="detail_ruang" class="font-extrabold text-gray-900 mt-1">{{ $firstItem->room->kode_ruang }}</p>
                                    <p id="detail_gedung" class="text-[11px] text-[#0A4D29] font-semibold">{{ $firstItem->room->nama_ruang }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">STATUS HONORARIUM</p>
                                    <p id="detail_honor" class="font-bold text-[#0A4D29] mt-1">{{ $firstItem->status_honorarium }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">BERITA ACARA (BAP)</p>
                                    <p id="detail_bap" class="font-bold text-gray-800 mt-1">{{ $firstItem->status_bap }}</p>
                                </div>
                            </div>

                            {{-- PIC Akademik & Tautan Zoom --}}
                            <div class="border-t border-b border-gray-100 py-3 space-y-2 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">👤 PIC Akademik</span>
                                    <span id="detail_pic" class="font-bold text-gray-900">{{ $firstItem->pic_akademik ?? 'Koordinator Prodi' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">📞 Kontak Narahubung</span>
                                    <span id="detail_kontak" class="font-bold text-[#0A4D29]">{{ $firstItem->pic_kontak ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-500">🎥 Tautan Cadangan</span>
                                    <span id="detail_zoom" class="font-mono text-[11px] text-[#F57C00] font-bold">{{ $firstItem->tautan_zoom ?? 'zoom.us/j/pradita' }}</span>
                                </div>
                            </div>

                            {{-- Form Update Status Pengajaran --}}
                            <form id="formUpdateEksternal" action="{{ route('pijar.jadwal-eksternal.status', $firstItem->id) }}" method="POST" class="space-y-2.5">
                                @csrf
                                @method('PATCH')
                                <div class="flex gap-2">
                                    <select name="status_eksternal" id="select_status_eksternal" class="flex-1 border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold">
                                        <option value="ready_to_teach" {{ $firstItem->status_eksternal === 'ready_to_teach' ? 'selected' : '' }}>Ready to Teach</option>
                                        <option value="ongoing" {{ $firstItem->status_eksternal === 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                        <option value="completed" {{ $firstItem->status_eksternal === 'completed' ? 'selected' : '' }}>Sudah Terlaksana</option>
                                        <option value="pending" {{ $firstItem->status_eksternal === 'pending' ? 'selected' : '' }}>Belum Terlaksana</option>
                                    </select>
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs cursor-pointer">
                                        Update Status
                                    </button>
                                </div>

                                <button type="submit" onclick="document.getElementById('select_status_eksternal').value='completed'" class="w-full py-2.5 rounded-xl bg-[#F57C00] hover:bg-[#e07000] text-white font-extrabold text-xs transition cursor-pointer shadow-xs">
                                    ✓ Tandai Selesai (Mark as Completed)
                                </button>
                            </form>
                        @endif
                    </div>

                </div>

                {{-- BANNER BAWAH: KEMITRAAN INDUSTRI TERINTEGRASI MBKM (PERSIS FOTO) --}}
                <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center text-xl shrink-0">
                            🤝
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-gray-900">Kemitraan Industri Terintegrasi MBKM</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Sistem PIJAR secara otomatis menerbitkan Berita Acara Kuliah Praktisi (BAP) dan merekapitulasi e-Presensi mahasiswa untuk pencairan honorarium pengajar mitra.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 shrink-0">
                        <div class="text-right">
                            <p class="text-[11px] text-gray-500 font-semibold">Tingkat Keterisian Kuota Praktisi</p>
                            <p class="text-xl font-extrabold text-[#0A4D29]">{{ $persenKuota }}% (Terisi)</p>
                        </div>
                        <div class="relative w-14 h-14 flex items-center justify-center">
                            <svg class="w-14 h-14 -rotate-90" viewBox="0 0 36 36">
                                <path class="text-gray-200" stroke-width="3.8" stroke="currentColor" fill="none"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="text-[#0A4D29]" stroke-dasharray="{{ $persenKuota }}, 100" stroke-width="3.8" stroke-linecap="round" stroke="currentColor" fill="none"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <span class="absolute text-[11px] font-extrabold text-gray-800">{{ round($persenKuota) }}%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function selectExternalSession(row) {
            // Highlight baris yang dipilih
            document.querySelectorAll('tbody tr').forEach(tr => {
                tr.classList.remove('bg-[#E8F5E9]/70', 'border-l-4', 'border-[#0A4D29]');
            });
            row.classList.add('bg-[#E8F5E9]/70', 'border-l-4', 'border-[#0A4D29]');

            const d = row.dataset;
            document.getElementById('formUpdateEksternal').action = '/pijar/jadwal-eksternal/' + d.id + '/status';
            document.getElementById('detail_prodi_kode').textContent = d.prodi + ' • ' + d.kodemk;
            document.getElementById('detail_nama_mk').textContent = d.namamk;
            document.getElementById('detail_fokus').textContent = d.fokus;
            document.getElementById('detail_dosen').textContent = d.dosen;
            document.getElementById('detail_mitra').textContent = d.mitra;
            document.getElementById('detail_jabatan').textContent = d.jabatan;
            document.getElementById('detail_hari').textContent = d.harisesi;
            document.getElementById('detail_waktu').textContent = d.waktusesi;
            document.getElementById('detail_ruang').textContent = d.ruang;
            document.getElementById('detail_gedung').textContent = d.gedung;
            document.getElementById('detail_honor').textContent = d.honor;
            document.getElementById('detail_bap').textContent = d.bap;
            document.getElementById('detail_pic').textContent = d.pic;
            document.getElementById('detail_kontak').textContent = d.kontak;
            document.getElementById('detail_zoom').textContent = d.zoom;
            document.getElementById('select_status_eksternal').value = d.status;

            const statusLabels = {
                'ready_to_teach': 'Ready to Teach',
                'ongoing': 'Sedang Berlangsung',
                'completed': 'Sudah Terlaksana',
                'pending': 'Belum Terlaksana'
            };
            document.getElementById('detail_status_badge').textContent = statusLabels[d.status] || 'Ready to Teach';
        }
    </script>

</body>
</html>