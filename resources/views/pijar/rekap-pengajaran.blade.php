<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Pengajaran Pihak Eksternal - Admin PIJAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30 print:hidden">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin &rsaquo; Akademik &rsaquo; Audit Pengajaran</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Rekapitulasi Pengajaran Pihak Eksternal</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('pijar.rekap-pengajaran') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari ruang, dosen, mitra, atau mata kuliah..." 
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

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- SIDEBAR MENU --}}
            <aside class="w-full lg:w-64 bg-[#0A4D29] rounded-2xl p-4 text-white flex flex-col justify-between lg:min-h-[820px] shrink-0 shadow-md print:hidden">
                <nav class="space-y-2">
                    <a href="{{ route('pijar.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('pijar.manajemen-jadwal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Manajemen Jadwal
                    </a>

                    <a href="{{ route('pijar.jadwal-eksternal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Jadwal Pihak Eksternal
                    </a>

                    <a href="{{ route('pijar.manajemen-akun') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Manajemen Akun
                    </a>

                    {{-- Menu Aktif: Rekap Pengajaran --}}
                    <a href="{{ route('pijar.rekap-pengajaran') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
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

                {{-- HEADER HALAMAN + TOMBOL EXPORT PDF, EXCEL, PRINT --}}
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#0A4D29]">● MODUL AUDIT & LOGISTIK AKADEMIK</span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-[11px] font-bold text-[#F57C00]">Tersertifikasi Presensi Digital</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Rekapitulasi Pengajaran Pihak Eksternal</h2>
                        <p class="text-xs text-gray-600 mt-1">Laporan komprehensif kegiatan perkuliahan dan pengajaran mitra industri eksternal periode berjalan.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 shrink-0 print:hidden">
                        <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-red-600 font-bold text-xs shadow-xs cursor-pointer">
                            📄 Export PDF
                        </button>
                        <a href="{{ route('pijar.rekap-pengajaran.export') }}" class="px-3.5 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-[#0A4D29] font-bold text-xs shadow-xs">
                            📊 Export Excel (.xlsx)
                        </a>
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs cursor-pointer">
                            🖨️ Print Report
                        </button>
                    </div>
                </div>

                {{-- BANNER STATUS LAPORAN AKTIF --}}
                <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center text-base shrink-0">
                            📅
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">STATUS LAPORAN AKTIF</p>
                            <p class="text-sm font-extrabold text-gray-900">Periode Laporan: {{ now()->translatedFormat('F Y') }} • Semester Ganjil 2026/2027</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-3.5 py-1.5 rounded-full">
                        ✓ Sinkronisasi Terakhir: Hari ini, {{ now()->format('H:i') }} WIB
                    </span>
                </div>

                {{-- 4 KARTU METRIK UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    {{-- 1. Total Pengajaran Terlaksana --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase leading-snug">TOTAL PENGAJARAN<br>TERLAKSANA</p>
                            <span class="w-9 h-9 rounded-xl bg-[#D5F5E3] text-[#0A4D29] flex items-center justify-center text-sm">☑</span>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900 my-3">{{ $totalSesi }} Sesi</p>
                        <div class="flex items-center gap-2 text-[11px]">
                            <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2 py-0.5 rounded">📈 +15%</span>
                            <span class="text-gray-500">vs periode lalu</span>
                        </div>
                    </div>

                    {{-- 2. Pihak Eksternal Aktif --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase leading-snug">PIHAK EKSTERNAL<br>AKTIF</p>
                            <span class="w-9 h-9 rounded-xl bg-[#E8F5E9] text-[#0A4D29] flex items-center justify-center text-sm">🪪</span>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900 my-3">{{ $totalPraktisi }} Praktisi</p>
                        <p class="text-[11px] text-gray-500">Tersebar dari <strong class="text-gray-800">{{ $totalMitra }} Perusahaan Mitra</strong></p>
                    </div>

                    {{-- 3. Program Studi Terlibat --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase leading-snug">PROGRAM STUDI<br>TERLIBAT</p>
                            <span class="w-9 h-9 rounded-xl bg-[#FFF0E0] text-[#F57C00] flex items-center justify-center text-sm">🎓</span>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900 my-3">{{ $totalProdi }} Prodi</p>
                        <p class="text-[11px] text-gray-500">Universitas Pradita (S1)</p>
                    </div>

                    {{-- 4. Total Jam Pengajaran --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase leading-snug">TOTAL JAM<br>PENGAJARAN</p>
                            <span class="w-9 h-9 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center text-sm">🕒</span>
                        </div>
                        <p class="text-3xl font-extrabold text-gray-900 my-3">{{ $totalJam }} Jam</p>
                        <p class="text-[11px] text-gray-500">Rata-rata alokasi <strong class="text-gray-800">{{ $rataRataJam }} Jam/Sesi</strong></p>
                    </div>
                </div>

                {{-- FILTER & PARAMETER LAPORAN --}}
                <form action="{{ route('pijar.rekap-pengajaran') }}" method="GET" class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 space-y-4 print:hidden">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-extrabold text-gray-900 flex items-center gap-2">
                            ⚙️ Filter & Parameter Laporan
                        </h3>
                        <a href="{{ route('pijar.rekap-pengajaran') }}" class="text-xs font-bold text-[#0A4D29] hover:underline">
                            Reset Semua Filter
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1">Bulan</label>
                            <select name="bulan" class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-50">
                                <option value="">Semua Bulan</option>
                                @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $namaBulan)
                                    <option value="{{ $num }}" {{ (string)$bulan === (string)$num ? 'selected' : '' }}>{{ $namaBulan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1">Tahun</label>
                            <select name="tahun" class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-50">
                                <option value="2026" {{ $tahun == '2026' ? 'selected' : '' }}>2026</option>
                                <option value="2025" {{ $tahun == '2025' ? 'selected' : '' }}>2025</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1">Program Studi</label>
                            <select name="prodi" class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-50">
                                <option value="">Semua Program Studi</option>
                                @foreach($listProdi as $p)
                                    <option value="{{ $p }}" {{ $prodi === $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1">Pengajar Eksternal</label>
                            <input type="text" name="pengajar" value="{{ $pengajar ?? '' }}" placeholder="Ketik nama praktisi..." class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1">Instansi Mitra</label>
                            <input type="text" name="mitra" value="{{ $mitra ?? '' }}" placeholder="Contoh: PT Telkom..." class="w-full border border-gray-200 rounded-xl px-3 py-2 bg-gray-50">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs cursor-pointer shadow-xs">
                            ⧩ Terapkan Filter
                        </button>
                    </div>
                </form>

                {{-- 3 KARTU ANALITIK VISUAL (BAR CHART MINGGUAN, TOP 5 PRODI, TOP 5 MITRA) --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                    {{-- 1. Distribusi Pengajaran per Minggu --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="font-extrabold text-base text-gray-900 leading-snug">Distribusi Pengajaran per Minggu</h4>
                                <span class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-extrabold px-2.5 py-1 rounded-lg">Total {{ $totalSesi }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jumlah perkuliahan terselesaikan setiap minggu pada periode aktif.</p>
                        </div>

                        <div class="grid grid-cols-4 gap-3 items-end h-40 pt-6 pb-2 px-2">
                            @foreach($minggu as $mKe => $jumlah)
                                @php
                                    $heightPct = max(18, round(($jumlah / $maxMingguVal) * 100));
                                    $isPeak = ($mKe === $puncakMinggu && $jumlah > 0);
                                @endphp
                                <div class="flex flex-col items-center gap-1.5 h-full justify-end">
                                    <span class="text-xs font-extrabold {{ $isPeak ? 'text-[#0A4D29]' : 'text-gray-600' }}">{{ $jumlah }}</span>
                                    <div class="w-full rounded-t-xl transition-all {{ $isPeak ? 'bg-[#0A4D29]' : 'bg-[#D5F5E3]' }}" @style(["height: {$heightPct}%"])></div>
                                    <span class="text-[11px] {{ $isPeak ? 'font-extrabold text-gray-900' : 'text-gray-500' }}">Minggu {{ $mKe }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-[#FAFCEB] border border-[#0A4D29]/15 rounded-xl px-3.5 py-2.5 flex justify-between items-center text-xs mt-2">
                            <span class="text-gray-600">Puncak Aktivitas: <strong class="text-gray-900">Minggu ke-{{ $puncakMinggu }}</strong></span>
                            <span class="font-extrabold text-[#0A4D29]">{{ $persenPuncak }}% Alokasi</span>
                        </div>
                    </div>

                    {{-- 2. Pengajaran Berdasarkan Program Studi --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="font-extrabold text-base text-gray-900 leading-snug">Pengajaran Berdasarkan Program Studi</h4>
                                <span class="text-[11px] font-bold text-gray-400 shrink-0">Top 5 Jurusan</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Penerimaan sesi praktisi industri per unit program studi.</p>
                        </div>

                        <div class="space-y-3.5 my-4">
                            @forelse($topProdi as $namaProdi => $jmlSesi)
                                @php $barWidth = round(($jmlSesi / $maxProdiVal) * 100); @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-bold text-gray-800">{{ $namaProdi }}</span>
                                        <span class="font-extrabold text-gray-900">{{ $jmlSesi }} Sesi</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-[#0A4D29] h-2 rounded-full" @style(["width: {$barWidth}%"])></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 text-center py-6">Belum ada data sesi selesai.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- 3. Mitra Instansi Terbanyak --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h4 class="font-extrabold text-base text-gray-900 leading-snug">Mitra Instansi Terbanyak</h4>
                                <span class="text-[#F57C00] text-base">🤝</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kontribusi entitas mitra industri tertinggi bulan ini.</p>
                        </div>

                        <div class="space-y-2.5 my-4">
                            @forelse($topMitra as $namaMitra => $jmlSesiMitra)
                                <div class="bg-gray-50 rounded-xl px-3.5 py-2.5 flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2.5 truncate">
                                        <span class="w-6 h-6 rounded-md bg-white border border-gray-200 text-gray-700 font-extrabold text-[11px] flex items-center justify-center shrink-0">
                                            {{ $loop->iteration }}
                                        </span>
                                        <span class="font-bold text-gray-800 truncate">{{ $namaMitra }}</span>
                                    </div>
                                    <span class="bg-[#D5F5E3] text-[#0A4D29] font-extrabold px-2.5 py-0.5 rounded-md text-[11px] shrink-0">
                                        {{ $jmlSesiMitra }} sesi
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400 text-center py-6">Belum ada data mitra industri.</p>
                            @endforelse
                        </div>
                    </div>

                </div>

                {{-- TABEL DAFTAR AUDIT PENGAJARAN TERLAKSANA (COMPLETED ONLY) --}}
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-extrabold text-base text-gray-900">Daftar Audit Pengajaran Terlaksana</h3>
                                <span class="bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                    ● Status: Completed Only
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">Menampilkan rekap sesi perkuliahan valid terverifikasi Berita Acara Presensi (BAP).</p>
                        </div>

                        <form action="{{ route('pijar.rekap-pengajaran') }}" method="GET" class="flex items-center gap-2 print:hidden">
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari dalam tabel..." class="border border-gray-200 rounded-xl px-3 py-1.5 text-xs bg-gray-50">
                            <a href="{{ route('pijar.rekap-pengajaran') }}" class="p-1.5 rounded-xl border border-gray-200 hover:bg-gray-100 text-xs font-bold">↻</a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4 w-12">NO</th>
                                    <th class="py-3.5 px-4">NAMA KEGIATAN / MATA KULIAH</th>
                                    <th class="py-3.5 px-4">NAMA PENGAJAR EKSTERNAL</th>
                                    <th class="py-3.5 px-4">INSTANSI MITRA</th>
                                    <th class="py-3.5 px-4">PROGRAM STUDI</th>
                                    <th class="py-3.5 px-4">TANGGAL</th>
                                    <th class="py-3.5 px-4">WAKTU SESI</th>
                                    <th class="py-3.5 px-4 text-center">DURASI</th>
                                    <th class="py-3.5 px-4 text-center">STATUS AUDIT</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($auditSchedules as $index => $item)
                                    @php
                                        $words = explode(' ', trim($item->dosen_pengajar));
                                        $initials = strtoupper(substr($words[0] ?? 'P', 0, 1) . substr($words[1] ?? '', 0, 1));
                                    @endphp
                                    <tr class="hover:bg-[#FAFCEB] transition">
                                        <td class="py-4 px-4 font-bold text-gray-400">{{ $auditSchedules->firstItem() + $index }}</td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-900 leading-snug">{{ $item->course->nama_mk }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $item->course->kode_mk }} • {{ $item->kelas }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-8 h-8 rounded-full bg-[#0A4D29] text-white font-extrabold text-[11px] flex items-center justify-center shrink-0">
                                                    {{ $initials }}
                                                </span>
                                                <span class="font-bold text-gray-900">{{ $item->dosen_pengajar }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-700">
                                            🏢 {{ $item->mitra_instansi ?? 'Mitra Industri Pradita' }}
                                        </td>
                                        <td class="py-4 px-4 text-gray-700 font-medium">
                                            {{ $item->course->prodi }}
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-800">
                                            {{ $item->hari_sesi }}
                                        </td>
                                        <td class="py-4 px-4 text-gray-600 font-mono text-[11px]">
                                            {{ $item->waktu_sesi }}
                                        </td>
                                        <td class="py-4 px-4 text-center font-extrabold text-gray-900">
                                            {{ number_format($item->course->sks ?? 3, 1) }} Jam
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">
                                                ✓ Completed
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-10 text-center text-gray-500">
                                            Belum ada sesi pengajaran eksternal berstatus Completed. Tandai selesai pada menu <strong>Jadwal Pihak Eksternal</strong> agar otomatis masuk ke laporan audit ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100">
                        {{ $auditSchedules->links() }}
                    </div>
                </div>

                {{-- BANNER BAWAH: VALIDASI & KEPATUHAN AUDIT AKADEMIK --}}
                <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-[#E8F5E9] text-[#0A4D29] flex items-center justify-center text-xl shrink-0">
                            🛡️
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-gray-900">Validasi & Kepatuhan Audit Akademik</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Data rekapitulasi disinkronkan langsung dengan Presensi Digital Mahasiswa & Berita Acara Perkuliahan (BAP). Seluruh data berstatus sah untuk keperluan pelaporan PD-DIKTI dan remunerasi kepakaran mitra.</p>
                        </div>
                    </div>

                    <span class="px-3.5 py-2 rounded-xl bg-gray-100 text-gray-700 font-mono font-bold text-[11px] shrink-0">
                        HASH: AUDIT-{{ date('Y-m') }}-PJR{{ $totalSesi }}
                    </span>
                </div>

            </div>
        </div>
    </div>

</body>
</html>