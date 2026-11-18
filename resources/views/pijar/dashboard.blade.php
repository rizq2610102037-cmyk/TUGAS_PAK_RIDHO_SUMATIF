<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin PIJAR & SUAR - Pradita University</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        {{-- Bagian Kiri Navbar (Hijau Tua) --}}
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <h1 class="text-white text-2xl font-extrabold tracking-tight">Dashboard</h1>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                {{-- Search Bar Dinamis --}}
                <form action="{{ route('pijar.dashboard') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari Ruang, Dosen, atau Mata Kuliah..." 
                        class="w-full pl-9 pr-4 py-1.5 rounded-full bg-[#E8F5E9] text-xs text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
                    >
                </form>

                {{-- Ikon Notifikasi --}}
                <button class="text-white hover:text-[#B7F4D1] transition relative p-1 cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($menungguApprove > 0)
                        <span class="absolute top-1 right-1 w-2 h-2 bg-[#F57C00] rounded-full"></span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Bagian Kanan Navbar (Oranye - Profil User dari DB) --}}
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
                <button onclick="this.parentElement.remove()" class="text-sm font-extrabold cursor-pointer">✕</button>
            </div>
        @endif

        {{-- BAGIAN ATAS: SIDEBAR TERPADU + KONTEN UTAMA --}}
        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

            {{-- SIDEBAR TERPADU --}}
            @include('layouts.sidebar-admin')

            {{-- KONTEN UTAMA KANAN --}}
            <div class="flex-1 min-w-0 w-full space-y-5">

                {{-- 1. WELCOME BANNER & 4 STATUS BAR --}}
                <div class="bg-[#0A4D29] rounded-2xl p-6 text-white shadow-md">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                        <div>
                            <span class="inline-block bg-[#B7F4D1] text-[#0A4D29] text-[11px] font-bold px-3 py-0.5 rounded-full mb-2">
                                Semester Ganjil - 2026/2027
                            </span>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-[#B7F4D1]">Selamat Datang, {{ Auth::user()->name }}!</h2>
                            <p class="italic text-white/90 text-sm mb-3">Admin Terpadu PIJAR & SUAR — Universitas Pradita</p>
                            <p class="text-xs text-white/80 max-w-xl leading-relaxed">
                                Berikut adalah ringkasan aktivitas akademik hari ini, sinkronisasi ruang kuliah, grafik perbandingan PIJAR & SUAR, serta pemantauan unggahan dokumen dosen.
                            </p>
                        </div>

                        <div class="flex flex-col gap-2.5 shrink-0">
                            <button onclick="window.print()" class="flex items-center gap-2 bg-[#E8F5E9] hover:bg-white text-[#0A4D29] font-bold text-xs px-4 py-2 rounded-full transition cursor-pointer shadow-sm">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                                Unduh / Cetak Laporan Tahunan
                            </button>
                        </div>
                    </div>

                    {{-- 4 Kotak Indikator Mint --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 mt-5">
                        <div class="bg-[#D5F5E3] text-[#0A4D29] rounded-xl p-3 text-center">
                            <p class="text-xs font-semibold">Ketersediaan Ruang</p>
                            <p class="text-base font-extrabold mt-0.5">({{ $ruangTersedia }}/{{ $totalRuang }} RUANG)</p>
                        </div>
                        <div class="bg-[#D5F5E3] text-[#0A4D29] rounded-xl p-3 text-center">
                            <p class="text-xs font-semibold">Dosen & Praktisi Aktif</p>
                            <p class="text-base font-extrabold mt-0.5">{{ $dosenAktif }} PENGAJAR</p>
                        </div>
                        <div class="bg-[#D5F5E3] text-[#0A4D29] rounded-xl p-3 text-center">
                            <p class="text-xs font-semibold">Status Sinkronisasi</p>
                            <p class="text-base font-extrabold mt-0.5">TERHUBUNG</p>
                        </div>
                        <div class="bg-[#D5F5E3] text-[#0A4D29] rounded-xl p-3 text-center">
                            <p class="text-xs font-semibold">Konflik Jadwal</p>
                            <p class="text-base font-extrabold mt-0.5">0 KASUS</p>
                        </div>
                    </div>
                </div>

                {{-- 2. 4 KARTU METRIK UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-[#0A4D29] text-white rounded-2xl p-4 flex flex-col justify-between shadow-sm">
                        <div class="flex justify-end">
                            <span class="bg-[#B7F4D1] text-[#0A4D29] text-[10px] font-bold px-2.5 py-0.5 rounded-full">Semua Prodi</span>
                        </div>
                        <div class="my-2">
                            <p class="text-[11px] uppercase tracking-wider text-white/80 font-semibold">TOTAL JADWAL</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-extrabold">{{ number_format($totalJadwal, 0, ',', '.') }}</span>
                                <span class="text-xs font-bold text-[#B7F4D1]">Data</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-white/80">Total pengajuan PIJAR & SUAR.</p>
                    </div>

                    <div class="bg-[#F57C00] text-white rounded-2xl p-4 flex flex-col justify-between shadow-sm">
                        <div class="flex justify-end">
                            <span class="bg-[#FFE57F] text-gray-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Tindakan Diperlukan</span>
                        </div>
                        <div class="my-2">
                            <p class="text-[11px] uppercase tracking-wider text-white/90 font-semibold">MENUNGGU APPROVAL</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-extrabold">{{ $menungguApprove }}</span>
                                <span class="text-xs font-semibold">Perlu Verifikasi</span>
                            </div>
                        </div>
                        <p class="text-[11px] font-medium text-white/95">Menunggu keputusan Admin.</p>
                    </div>

                    <div class="bg-[#0A4D29] text-white rounded-2xl p-4 flex flex-col justify-between shadow-sm">
                        <div class="flex justify-end">
                            <span class="bg-[#B7F4D1] text-[#0A4D29] text-[10px] font-bold px-2.5 py-0.5 rounded-full">Terverifikasi</span>
                        </div>
                        <div class="my-2">
                            <p class="text-[11px] uppercase tracking-wider text-white/80 font-semibold">JADWAL DISETUJUI</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-extrabold">{{ number_format($jadwalDisetujui, 0, ',', '.') }}</span>
                                <span class="text-xs font-semibold text-[#B7F4D1]">Valid</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-white/80">Akun email kampus pengajar aktif.</p>
                    </div>

                    <div class="bg-[#F57C00] text-white rounded-2xl p-4 flex flex-col justify-between shadow-sm">
                        <div class="flex justify-end">
                            <span class="bg-[#FFE57F] text-gray-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Program SUAR</span>
                        </div>
                        <div class="my-2">
                            <p class="text-[11px] uppercase tracking-wider text-white/90 font-semibold">JADWAL PIHAK EKSTERNAL</p>
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-extrabold">{{ $jadwalEksternal }}</span>
                                <span class="text-xs font-semibold">Mitra & Praktisi</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-white/90">Dosen tamu & praktisi industri.</p>
                    </div>
                </div>

                {{-- 3. GRAFIK ANALITIK REVISI PAK RIDHO (PIJAR VS SUAR 5 TAHUN & PER PRODI) --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900">Tren PIJAR vs SUAR (5 Tahun)</h3>
                                <p class="text-[11px] text-gray-500">Minggu ini: <strong>{{ $weeklyPijar }} PIJAR</strong> &bull; <strong>{{ $weeklySuar }} SUAR</strong></p>
                            </div>
                            <span class="bg-[#E8F5E9] text-[#0A4D29] text-[10px] font-bold px-2.5 py-1 rounded-full">2026 - 2031</span>
                        </div>
                        <canvas id="chartPijarSuar" height="150"></canvas>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-base font-extrabold text-gray-900">Perbandingan Penggunaan SUAR per Prodi</h3>
                                <p class="text-[11px] text-gray-500">Peringkat prodi terbanyak hingga tersedikit memakai praktisi</p>
                            </div>
                            <span class="bg-[#FFF0E0] text-[#F57C00] text-[10px] font-bold px-2.5 py-1 rounded-full">Per Prodi</span>
                        </div>
                        <canvas id="chartSuarProdi" height="150"></canvas>
                    </div>
                </div>

                {{-- 4. PEMANTAUAN UNGGAHAN DOKUMEN DOSEN & PRAKTISI (RPS / MODUL) --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Pemantauan Unggahan Dokumen Dosen & Praktisi</h3>
                            <p class="text-xs text-gray-500">Status kelengkapan dokumen pra-pelaksanaan (RPS, Silabus, Modul 1–13) untuk jadwal yang telah di-ACC.</p>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs font-bold">
                            <span class="px-3 py-1 rounded-full bg-[#D5F5E3] text-[#0A4D29]">✓ Sudah Unggah: {{ $sudahUnggah->count() }}</span>
                            <span class="px-3 py-1 rounded-full bg-[#FFF0E0] text-[#F57C00]">⚠️ Belum Unggah: {{ $belumUnggah->count() }}</span>
                        </div>
                    </div>

                    @if($belumUnggah->count() > 0)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($belumUnggah->take(8) as $itemBelum)
                                <span class="text-[11px] bg-red-50 border border-red-200 text-red-700 px-3 py-1 rounded-lg font-semibold">
                                    {{ $itemBelum->praktisi_nama ?: $itemBelum->dosen_pengajar }} — {{ $itemBelum->course->nama_mk ?? '-' }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-[#0A4D29] font-semibold">Seluruh dosen/praktisi pada jadwal disetujui sudah mengunggah dokumen RPS/Modul.</p>
                    @endif
                </div>

                {{-- 5. WEEKLY OVERVIEW & MONTHLY OVERVIEW --}}
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-4">
                    
                    {{-- Weekly Overview (7 Kolom) --}}
                    <div class="xl:col-span-7 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <span class="inline-block bg-[#F57C00] text-white text-[10px] font-bold px-3 py-0.5 rounded-full mb-1">
                                Minggu Ini ({{ \Carbon\Carbon::now()->startOfWeek()->translatedFormat('d M') }} - {{ \Carbon\Carbon::now()->endOfWeek()->translatedFormat('d M Y') }})
                            </span>
                            <h3 class="text-xl font-extrabold text-gray-900">Weekly Overview</h3>
                            <p class="text-xs text-gray-500">Aktivitas perkuliahan dan submission jadwal 7 hari terakhir.</p>

                            <div class="grid grid-cols-3 gap-2.5 mt-4">
                                <div class="bg-[#D5F5E3] rounded-xl p-3">
                                    <p class="text-[11px] font-bold text-[#0A4D29]">Kelas Disetujui</p>
                                    <p class="text-2xl font-extrabold text-[#0A4D29] my-0.5">{{ $weeklyTerlaksana }}</p>
                                    <p class="text-[10px] text-[#0A4D29]">Sesuai Agenda</p>
                                </div>
                                <div class="bg-[#FFF0E0] rounded-xl p-3">
                                    <p class="text-[11px] font-bold text-[#F57C00]">Menunggu Verifikasi</p>
                                    <p class="text-2xl font-extrabold text-[#F57C00] my-0.5">{{ $weeklyMenunggu }}</p>
                                    <p class="text-[10px] text-[#F57C00]">Tinjau Segera</p>
                                </div>
                                <div class="bg-[#FADBD8] rounded-xl p-3">
                                    <p class="text-[11px] font-bold text-[#C0392B]">Revisi / Ditolak</p>
                                    <p class="text-2xl font-extrabold text-[#C0392B] my-0.5">{{ $weeklyBatal }}</p>
                                    <p class="text-[10px] text-[#C0392B]">Perlu Penyesuaian</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="grid grid-cols-7 gap-3 h-36 items-end px-2">
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[25%]"></div>
                                    <div class="bg-[#0A4D29] h-[55%]"></div>
                                    <div class="bg-[#F4EE1A] h-[20%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[12%]"></div>
                                    <div class="bg-[#0A4D29] h-[80%]"></div>
                                    <div class="bg-[#F4EE1A] h-[8%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[18%]"></div>
                                    <div class="bg-[#0A4D29] h-[64%]"></div>
                                    <div class="bg-[#F4EE1A] h-[18%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[20%]"></div>
                                    <div class="bg-[#0A4D29] h-[55%]"></div>
                                    <div class="bg-[#F4EE1A] h-[25%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[42%]"></div>
                                    <div class="bg-[#0A4D29] h-[50%]"></div>
                                    <div class="bg-[#F4EE1A] h-[8%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[55%]"></div>
                                    <div class="bg-[#0A4D29] h-[40%]"></div>
                                    <div class="bg-[#F4EE1A] h-[5%]"></div>
                                </div>
                                <div class="h-full flex flex-col justify-end rounded-sm overflow-hidden">
                                    <div class="bg-[#B7F4D1] h-[75%]"></div>
                                    <div class="bg-[#0A4D29] h-[22%]"></div>
                                    <div class="bg-[#F4EE1A] h-[3%]"></div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-center gap-4 mt-4 text-[11px] font-medium text-gray-700">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-[#B7F4D1] inline-block"></span> Slot Kosong
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-[#0A4D29] inline-block"></span> Disetujui / Aktif
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-[#F4EE1A] inline-block"></span> Menunggu Review
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Monthly Overview (5 Kolom) --}}
                    <div class="xl:col-span-5 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <span class="inline-block bg-[#F57C00] text-white text-[10px] font-bold px-3 py-0.5 rounded-full mb-1">
                                {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                            </span>
                            <h3 class="text-xl font-extrabold text-gray-900">Monthly Overview</h3>
                            <p class="text-xs text-gray-500">Statistik mengajar bulan {{ \Carbon\Carbon::now()->translatedFormat('F') }}</p>

                            <div class="bg-[#F57C00] rounded-xl p-4 text-white mt-4 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider">TOTAL JADWAL BULAN INI</p>
                                    <div class="flex items-baseline gap-1 mt-1">
                                        <span class="text-3xl font-extrabold">{{ $monthlyTotal }}</span>
                                        <span class="text-xs font-medium">Sesi</span>
                                    </div>
                                </div>

                                <div class="relative w-20 h-20 flex items-center justify-center">
                                    <svg class="w-20 h-20 -rotate-90" viewBox="0 0 36 36">
                                        <path class="text-[#FFF0E0]" stroke-width="3.8" stroke="currentColor" fill="none"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                        <path class="text-[#0A4D29]" stroke-dasharray="{{ $monthlyPersentase }}, 100" stroke-width="3.8" stroke-linecap="round" stroke="currentColor" fill="none"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    </svg>
                                    <div class="absolute text-center leading-tight">
                                        <span class="block text-sm font-extrabold">{{ $monthlyPersentase }}%</span>
                                        <span class="block text-[9px]">Tercapai</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 mt-5">
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-[#0A4D29]">• Terlaksana Sesuai Rencana</span>
                                    <span class="text-gray-600">{{ $monthlyTerlaksana }} Sesi</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-[#0A4D29] h-2.5 rounded-full w-[var(--w)]" style="--w: {{ $monthlyPersentase }}%;"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-[#C0392B]">• Belum Terlaksana (Sisa Bulan)</span>
                                    <span class="text-gray-600">{{ $monthlyBelum }} Sesi</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    @php $persenBelum = $monthlyTotal > 0 ? round(($monthlyBelum / $monthlyTotal) * 100) : 0; @endphp
                                   <div class="bg-[#5D1916] h-2.5 rounded-full w-[var(--w)]" style="--w: {{ $persenBelum }}%;"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs font-bold mb-1">
                                    <span class="text-[#8E44AD]">• Total Pengajaran Eksternal (SUAR)</span>
                                    <span class="text-gray-600">{{ $monthlyEksternal }} Sesi</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    @php $persenEksternal = $monthlyTotal > 0 ? round(($monthlyEksternal / $monthlyTotal) * 100) : 0; @endphp
                                    <div class="bg-[#5D1916] h-2.5 rounded-full w-[var(--w)]" style="--w: {{ $persenEksternal }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- BAGIAN BAWAH: AKTIVITAS PENGAJUAN TERBARU --}}
        <section class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="bg-[#F57C00] px-6 py-4 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-extrabold">Aktivitas Pengajuan Terbaru</h3>
                    <p class="text-xs text-white/90">Perbaruan dari Kaprodi, Sekprodi, Satuan Unit Kaprodi & Riset (SUAR) dan Biro Kerjasama (KERMA)</p>
                </div>
                @if(!empty($search))
                    <a href="{{ route('pijar.dashboard') }}" class="bg-white text-[#F57C00] text-xs font-bold px-3 py-1 rounded-full hover:bg-gray-100">
                        Reset Pencarian: "{{ $search }}" ✕
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F7A854] text-gray-900 text-xs font-extrabold uppercase tracking-wider">
                            <th class="py-3 px-4 border-r border-white/40 w-12 text-center">NO.</th>
                            <th class="py-3 px-4 border-r border-white/40">NAMA KEGIATAN / NAMA KULIAH</th>
                            <th class="py-3 px-4 border-r border-white/40">SEMESTER & PRODI</th>
                            <th class="py-3 px-4 border-r border-white/40">PENGAJU DAN PERAN</th>
                            <th class="py-3 px-4 border-r border-white/40">TANGGAL PENGAJUAN</th>
                            <th class="py-3 px-4 border-r border-white/40 text-center">STATUS</th>
                            <th class="py-3 px-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs">
                        @forelse ($recentSchedules as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-4 text-center font-semibold">{{ $index + 1 }}.</td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-gray-900">{{ $item->course->nama_mk }}</p>
                                    <p class="text-gray-500 mt-0.5">
                                        <span class="underline">{{ $item->course->kode_mk }}</span> - {{ $item->room->kode_ruang }} - {{ $item->course->sks }} SKS
                                    </p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-gray-800">{{ $item->course->prodi }}</p>
                                    <p class="text-[11px] text-gray-500">TA {{ $item->tahun_ajaran ?? '2026-2027' }} • Sem. {{ $item->semester_jenis ?? 'Ganjil' }} ({{ $item->semester_angka ?? 1 }})</p>
                                </td>
                                <td class="py-4 px-4">
                                    <p class="font-bold text-gray-900 whitespace-pre-line">{{ $item->dosen_pengajar }}</p>
                                    <p class="italic text-gray-500">{{ $item->peran_pengaju }}</p>
                                </td>
                                <td class="py-4 px-4 font-medium">{{ $item->tanggal_pengajuan->translatedFormat('d F Y - H.i') }} WIB</td>
                                <td class="py-4 px-4 text-center">
                                    @if ($item->status === 'pending_approval')
                                        <span class="inline-block bg-[#F57C00] text-white font-bold px-3 py-1 rounded-full text-[11px]">
                                            Pending Approval
                                        </span>
                                    @elseif ($item->status === 'approved')
                                        <span class="inline-block bg-[#0A4D29] text-white font-bold px-5 py-1 rounded-full text-[11px]">
                                            Approved
                                        </span>
                                    @elseif ($item->status === 'revision_requested')
                                        <span class="inline-block bg-[#FF0000] text-white font-bold px-3.5 py-1 rounded-full text-[11px] leading-tight">
                                            Revision<br>Requested
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-500 text-white font-bold px-3 py-1 rounded-full text-[11px]">
                                            Ditolak / Cancelled
                                        </span>
                                        @if($item->alasan_penolakan)
                                            <p class="text-[10px] text-red-600 mt-1">{{ $item->alasan_penolakan }}</p>
                                        @endif
                                    @endif
                                </td>
                                
                                <td class="py-4 px-4 text-center">
                                    <button 
                                        type="button"
                                        data-item='@json($item)'
                                        onclick="openEditModal(this)"
                                        class="bg-[#B7F4D1] hover:bg-[#9ae8bc] text-[#0A4D29] font-bold px-3.5 py-1 rounded-full text-[11px] transition cursor-pointer">
                                        @if ($item->status === 'pending_approval')
                                            Review Pengajuan
                                        @elseif ($item->status === 'approved')
                                            Lihat Detail
                                        @else
                                            Tinjau Revisi
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500 font-medium">
                                    Tidak ada data pengajuan jadwal ditemukan di database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- MODAL REVIEW & EDIT JADWAL --}}
    <div id="modalEdit" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Review & Keputusan Jadwal</h3>
                <button onclick="document.getElementById('modalEdit').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 font-bold cursor-pointer">✕</button>
            </div>
            <form id="formEdit" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-gray-500 mb-1">Mata Kuliah</label>
                    <input type="text" id="edit_mk" readonly class="w-full bg-gray-100 border border-gray-300 rounded-lg px-3 py-2 font-bold text-gray-700">
                </div>
                <div>
                    <label class="block font-bold mb-1">Ruang Kuliah</label>
                    <select name="room_id" id="edit_room" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->kode_ruang }} - {{ $room->nama_ruang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Dosen / Praktisi Pengajar</label>
                    <input type="text" name="dosen_pengajar" id="edit_dosen" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold mb-1">Email Kampus Pengajar (Otomatis aktifkan akun saat Approved)</label>
                    <input type="email" name="dosen_email_kampus" id="edit_email" placeholder="Kosongkan untuk generate otomatis (@pradita.ac.id)" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block font-bold mb-1">Keputusan Status</label>
                    <select name="status" id="edit_status" onchange="toggleAlasanPenolakan()" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-bold">
                        <option value="pending_approval">Pending Approval</option>
                        <option value="approved">Approved (Setujui & Buat Akun Pengajar)</option>
                        <option value="revision_requested">Revision Requested (Minta Revisi)</option>
                        <option value="cancelled">Cancelled (Tolak Pengajuan)</option>
                    </select>
                </div>
                <div id="box_alasan_penolakan" class="hidden">
                    <label class="block font-bold text-red-700 mb-1">Alasan Penolakan (Tampil ke Kaprodi)</label>
                    <textarea name="alasan_penolakan" id="edit_alasan" rows="2" placeholder="Tuliskan alasan penolakan..." class="w-full border border-red-300 rounded-lg px-3 py-2 bg-red-50"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#F57C00] text-white font-bold cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(item) {
            document.getElementById('formEdit').action = '/pijar/schedules/' + item.id;
            document.getElementById('edit_mk').value = item.course ? item.course.nama_mk : '';
            document.getElementById('edit_room').value = item.room_id;
            document.getElementById('edit_dosen').value = item.dosen_pengajar || '';
            document.getElementById('edit_email').value = item.dosen_email_kampus || '';
            document.getElementById('edit_status').value = item.status;
            document.getElementById('edit_alasan').value = item.alasan_penolakan || '';
            toggleAlasanPenolakan();
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function toggleAlasanPenolakan() {
            const st = document.getElementById('edit_status').value;
            document.getElementById('box_alasan_penolakan').classList.toggle('hidden', st !== 'cancelled');
        }

        // Chart 1: PIJAR vs SUAR 5 Tahun
        // Chart 1: PIJAR vs SUAR 5 Tahun
        const daftarTahun = JSON.parse('{!! json_encode($daftarTahun) !!}');
        const chartPijar  = JSON.parse('{!! json_encode($chartPijar) !!}');
        const chartSuar   = JSON.parse('{!! json_encode($chartSuar) !!}');

        new Chart(document.getElementById('chartPijarSuar'), {
            type: 'bar',
            data: {
                labels: daftarTahun,
                datasets: [
                    { label: 'PIJAR (Reguler)', data: chartPijar, backgroundColor: '#0A4D29', borderRadius: 6 },
                    { label: 'SUAR (Praktisi)', data: chartSuar, backgroundColor: '#F57C00', borderRadius: 6 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });

        // Chart 2: Perbandingan SUAR per Prodi
        const prodiLabels = JSON.parse('{!! json_encode($prodiLabels) !!}');
        const prodiCounts = JSON.parse('{!! json_encode($prodiCounts) !!}');

        new Chart(document.getElementById('chartSuarProdi'), {
            type: 'bar',
            data: {
                labels: prodiLabels,
                datasets: [
                    { label: 'Kelas SUAR', data: prodiCounts, backgroundColor: '#0A4D29', borderRadius: 6 }
                ]
            },
            options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>