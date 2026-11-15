<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal & Approval - Admin PIJAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin &rsaquo; Akademik</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Manajemen Jadwal & Approval</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('pijar.manajemen-jadwal') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari ruang, dosen, prodi, atau mata kuliah..." 
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
            <aside class="w-full lg:w-64 bg-[#0A4D29] rounded-2xl p-4 text-white flex flex-col justify-between lg:min-h-[670px] shrink-0 shadow-md">
                <nav class="space-y-2">
                    <a href="{{ route('pijar.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V4zM3 12a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1v-4zm8 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                        Dashboard
                    </a>

                    {{-- Menu Aktif: Manajemen Jadwal --}}
                    <a href="{{ route('pijar.manajemen-jadwal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
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

            {{-- KONTEN UTAMA MANAJEMEN JADWAL --}}
            <div class="flex-1 w-full space-y-5">

                {{-- Header & Ringkasan Filter Cepat --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-gray-100">
                        <div>
                            <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-3 py-0.5 rounded-full mb-1">
                                Periode Aktif: Semester Ganjil 2026/2027
                            </span>
                            <h2 class="text-2xl font-extrabold text-gray-900">Verifikasi & Validasi Jadwal Perkuliahan</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Kelola pengajuan jadwal dari seluruh Program Studi, periksa ketersediaan ruang, dan berikan keputusan persetujuan.</p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a href="{{ route('pijar.manajemen-jadwal') }}" class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">
                                ↻ Reset Filter
                            </a>
                            <button type="button" onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white text-xs font-bold shadow-sm cursor-pointer">
                                + Tambah Jadwal Cepat
                            </button>
                        </div>
                    </div>

                    {{-- Tab Filter Status --}}
                    <div class="flex flex-wrap items-center gap-2 pt-4 text-xs">
                        <a href="{{ route('pijar.manajemen-jadwal') }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ !$status ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Semua Jadwal ({{ $totalJadwal }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'pending_approval']) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'pending_approval' ? 'bg-[#F57C00] text-white' : 'bg-[#FFF0E0] text-[#F57C00] hover:bg-[#ffe1bf]' }}">
                            Menunggu Verifikasi ({{ $menungguApprove }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'approved' ? 'bg-[#0A4D29] text-white' : 'bg-[#D5F5E3] text-[#0A4D29] hover:bg-[#bcefd2]' }}">
                            Disetujui ({{ $jadwalDisetujui }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'revision_requested']) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'revision_requested' ? 'bg-[#FF0000] text-white' : 'bg-[#FADBD8] text-[#C0392B] hover:bg-[#f5c6c2]' }}">
                            Revisi / Ditolak ({{ $jadwalRevisi }})
                        </a>
                    </div>
                </div>

                {{-- Tabel Daftar Pengajuan Jadwal --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#0A4D29] text-white text-[11px] font-bold uppercase tracking-wider">
                                    <th class="py-3.5 px-4">ID & Mata Kuliah</th>
                                    <th class="py-3.5 px-4">Program Studi</th>
                                    <th class="py-3.5 px-4">Pengampu & Pengaju</th>
                                    <th class="py-3.5 px-4">Sesi & Ruangan</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-xs">
                                @forelse ($schedules as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-4 px-4">
                                            <span class="text-[10px] font-mono font-bold text-gray-400">REQ-{{ $item->tanggal_pengajuan->format('Ymd') }}-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}</span>
                                            <p class="font-bold text-gray-900 text-sm">{{ $item->course->kode_mk }} - {{ $item->course->nama_mk }}</p>
                                            <p class="text-gray-500 mt-0.5">{{ $item->course->sks }} SKS · {{ $item->kelas }}</p>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-800">
                                            {{ $item->course->prodi }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-900 whitespace-pre-line">{{ $item->dosen_pengajar }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Pengaju: {{ $item->peran_pengaju }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-800">{{ $item->hari_sesi }}</p>
                                            <p class="text-gray-500">{{ $item->waktu_sesi }}</p>
                                            <span class="inline-block mt-1 bg-[#E8F5E9] text-[#0A4D29] font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $item->room->kode_ruang }} (Kapasitas: {{ $item->room->kapasitas }})
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($item->status === 'pending_approval')
                                                <span class="inline-block bg-[#FFF0E0] text-[#F57C00] border border-[#F57C00]/30 font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ● Menunggu Verifikasi
                                                </span>
                                            @elseif ($item->status === 'approved')
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3.5 py-1 rounded-full text-[11px]">
                                                    ✓ Disetujui
                                                </span>
                                            @elseif ($item->status === 'revision_requested')
                                                <span class="inline-block bg-[#FADBD8] text-[#C0392B] font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ! Minta Revisi
                                                </span>
                                            @else
                                                <span class="inline-block bg-gray-200 text-gray-700 font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ✕ Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button 
                                                type="button"
                                                onclick="openApprovalModal(this)"
                                                data-id="{{ $item->id }}"
                                                data-reqid="REQ-{{ $item->tanggal_pengajuan->format('Ymd') }}-{{ str_pad($item->id, 3, '0', STR_PAD_LEFT) }}"
                                                data-kodemk="{{ $item->course->kode_mk }}"
                                                data-namamk="{{ $item->course->nama_mk }}"
                                                data-prodi="{{ $item->course->prodi }}"
                                                data-sks="{{ $item->course->sks }}"
                                                data-kelas="{{ $item->kelas }}"
                                                data-dosen="{{ $item->dosen_pengajar }}"
                                                data-pengaju="{{ $item->peran_pengaju }}"
                                                data-waktuajuan="{{ $item->tanggal_pengajuan->translatedFormat('d F Y, H:i') }} WIB"
                                                data-harisesi="{{ $item->hari_sesi }}"
                                                data-waktusesi="{{ $item->waktu_sesi }}"
                                                data-roomid="{{ $item->room_id }}"
                                                data-roomkode="{{ $item->room->kode_ruang }}"
                                                data-roomkapasitas="{{ $item->room->kapasitas }}"
                                                data-catatan="{{ $item->catatan_pengaju ?? 'Pengajuan jadwal perkuliahan reguler sesuai kurikulum program studi.' }}"
                                                data-status="{{ $item->status }}"
                                                data-praktisi="{{ $item->praktisi_nama ?? ($item->is_external ? $item->dosen_pengajar : 'Perkuliahan Reguler (Non-Praktisi)') }}"
                                                data-pertemuanke="{{ $item->pertemuan_ke ?? 1 }}"
                                                data-kuotamk="{{ $kuotaMk[$item->course_id] ?? 0 }}"
                                                data-kuotaprodi="{{ $kuotaProdi[$item->course->prodi] ?? 0 }}"
                                                data-dokumen="{{ $item->dokumen_bahan_ajar ? asset('storage/' . $item->dokumen_bahan_ajar) : '' }}"
                                                data-iks="{{ $item->nomor_iks ?? '' }}"
                                                data-presensi="{{ $item->status_presensi_pijar ?? 'Formulir Diberikan' }}"
                                                class="bg-[#0A4D29] hover:bg-[#F57C00] text-white font-bold px-4 py-1.5 rounded-lg text-[11px] transition cursor-pointer shadow-xs">
                                                Periksa & Putuskan

                                                {{-- Blok Kepatuhan SOP PRU/SPMI/SD-BM10 --}}
                                                    <div class="bg-[#FAFCEB] border border-[#0A4D29]/20 rounded-xl p-4 mt-4 text-xs space-y-2.5">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A4D29]">KONTROL KUOTA & DOKUMEN SOP PIJAR (SD-BM-10)</p>
                                                            <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2 py-0.5 rounded text-[10px]">Pertemuan ke-<span id="modal_pertemuan_ke">1</span></span>
                                                        </div>
                                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                                            <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                                                                <p class="text-[10px] text-gray-500">Kuota MK (Maks 7x)</p>
                                                                <p class="font-extrabold text-gray-900 mt-0.5"><span id="modal_kuota_mk">1</span> / 7 Pertemuan</p>
                                                            </div>
                                                            <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                                                                <p class="text-[10px] text-gray-500">Kuota Prodi/Tahun (Maks 28x)</p>
                                                                <p class="font-extrabold text-[#0A4D29] mt-0.5"><span id="modal_kuota_prodi">1</span> / 28 Pertemuan</p>

                                                                
                                                            </div>

                                                            {{-- INI BAGIAN LANGKAH 2 --}}
                                                            <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                                                                <p class="text-[10px] text-gray-500">Berkas RPS / RPS Mini</p>
                                                                <a id="modal_link_dokumen" href="#" target="_blank" class="font-bold text-[#F57C00] hover:underline mt-0.5 inline-block">
                                                                    📄 Unduh / Lihat Berkas
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            Belum ada data jadwal sesuai filter yang dipilih.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $schedules->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PERSETUJUAN JADWAL (PERSIS STRUKTUR FOTO) --}}
    <div id="modalApproval" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-3xl w-full p-6 md:p-7 shadow-2xl my-8 relative">

            {{-- Header Modal --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span id="modal_badge_status" class="bg-[#FFF0E0] text-[#F57C00] text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                ● Menunggu Verifikasi Admin
                            </span>
                            <span id="modal_req_id" class="text-xs font-mono text-gray-500 font-semibold">ID: REQ-20260324-001</span>
                        </div>
                        <h3 id="modal_judul_mk" class="text-xl md:text-2xl font-extrabold text-gray-900 leading-snug">
                            Persetujuan Jadwal: Kode - Nama Mata Kuliah
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Lakukan verifikasi integritas data mata kuliah, beban dosen, dan ketersediaan ruangan.</p>
                    </div>
                </div>

                <button type="button" onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-700 p-1 text-lg font-bold cursor-pointer">✕</button>
            </div>

            {{-- Alert Box Peringatan Validasi Ruangan --}}
            <div class="mt-4 bg-[#FFF6E5] border border-[#F5A642]/50 rounded-xl p-3.5 flex items-start gap-3">
                <span class="text-[#F57C00] text-lg leading-none mt-0.5">⚠</span>
                <div class="text-xs text-[#8A4B08] leading-relaxed">
                    <p class="font-bold text-[#6E3B04]">Peringatan Validasi Ruangan & Alokasi Perangkat</p>
                    <p class="mt-0.5">
                        Pastikan ketersediaan ruangan <strong id="modal_alert_ruang">Smart Class</strong> dan perangkat pendukung sebelum memberikan persetujuan final.
                    </p>
                </div>
            </div>

            {{-- Grid 2 Kolom: Spesifikasi Akademik & Pihak Pengampu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                {{-- Kolom Kiri --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-2.5 text-xs">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">SPESIFIKASI AKADEMIK</p>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Program Studi</span>
                        <span id="modal_prodi" class="font-bold text-gray-900 text-right">Sistem Informasi</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Beban Studi</span>
                        <span id="modal_sks" class="font-bold text-gray-900">3 SKS</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Semester / Kelas</span>
                        <span id="modal_kelas" class="font-bold text-gray-900">Kelas A</span>
                    </div>
                    <div class="flex justify-between items-center gap-2">
                        <span class="text-gray-500">Jenis Kegiatan</span>
                        <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2.5 py-0.5 rounded-md text-[11px]">Perkuliahan Reguler Tatap Muka</span>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-2.5 text-xs">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PIHAK PENGAMPU & PENGUSUL</p>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Dosen Utama</span>
                        <span id="modal_dosen" class="font-bold text-gray-900 text-right whitespace-pre-line">Nama Dosen</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Status Ikatan</span>
                        <span class="font-bold text-[#0A4D29]">Dosen Tetap Homebase</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Pengaju Sesi</span>
                        <span id="modal_pengaju" class="font-bold text-gray-900 text-right">Kaprodi</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Waktu Pengajuan</span>
                        <span id="modal_waktu_ajuan" class="font-bold text-gray-900">24 Maret 2026</span>
                    </div>
                </div>
            </div>

            {{-- Jadwal Sesi & Alokasi Ruang --}}
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2.5">JADWAL SESI & ALOKASI RUANG</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Hari & Tanggal</p>
                        <p id="modal_hari_sesi" class="font-extrabold text-gray-900 text-sm mt-0.5">Rabu, 26 Maret 2026</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Semester Ganjil 2026/2027</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Waktu / Durasi</p>
                        <p id="modal_waktu_sesi" class="font-extrabold text-gray-900 text-sm mt-0.5">08:00 - 10:30 WIB</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Sesuai Bobot SKS</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Ruang & Kapasitas</p>
                        <p id="modal_ruang_info" class="font-extrabold text-gray-900 text-sm mt-0.5">Smart Class</p>
                        <p id="modal_ruang_kapasitas" class="text-[10px] font-bold text-[#0A4D29] mt-0.5">Kapasitas: 45 Kursi</p>
                    </div>
                </div>
            </div>

            {{-- Catatan dari Pengaju --}}
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">CATATAN DARI PENGAJU (KAPRODI):</p>
                <div class="bg-white border border-gray-200/80 rounded-lg p-3 text-xs italic text-gray-700">
                    “<span id="modal_catatan_pengaju">Pengajuan jadwal perkuliahan reguler.</span>”
                </div>
            </div>

            {{-- Form Keputusan Administrator --}}
            <form id="formDecision" method="POST" class="mt-5 pt-4 border-t border-gray-200">
                @csrf
                @method('PUT')
                <input type="hidden" name="dosen_pengajar" id="input_dosen">
                <input type="hidden" name="status" id="input_status" value="approved">

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-3">
                    <h4 class="text-sm font-extrabold text-gray-900">Formulir Keputusan Administrator</h4>
                    <div class="flex items-center gap-2 text-xs">
                        <label class="font-bold text-gray-600">Penyesuaian Ruang:</label>
                        <select name="room_id" id="input_room_id" class="border border-gray-300 rounded-lg px-2.5 py-1 text-xs font-semibold">
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->kode_ruang }} ({{ $room->kapasitas }} Kursi)</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <input type="text" name="catatan_admin" placeholder="Tambahkan catatan keputusan atau alasan revisi (opsional)..." class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#0A4D29]">
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="submit" onclick="document.getElementById('input_status').value='approved'" class="px-4 py-2 rounded-xl bg-[#D5F5E3] hover:bg-[#0A4D29] text-[#0A4D29] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✓ Setujui (Approve)
                        </button>
                        <button type="submit" onclick="document.getElementById('input_status').value='revision_requested'" class="px-4 py-2 rounded-xl bg-[#FFF0E0] hover:bg-[#F57C00] text-[#F57C00] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✎ Minta Revisi
                        </button>
                        <button type="submit" onclick="document.getElementById('input_status').value='cancelled'" class="px-4 py-2 rounded-xl bg-[#FADBD8] hover:bg-[#C0392B] text-[#C0392B] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✕ Tolak Jadwal
                        </button>
                    </div>

                    <button type="button" onclick="closeApprovalModal()" class="px-4 py-2 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold text-xs cursor-pointer">
                        Tutup Formulir
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-2">Persetujuan akan disimpan secara permanen pada log riwayat akademik.</p>
            </form>

        </div>
    </div>

    {{-- MODAL TAMBAH JADWAL CEPAT --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Tambah Jadwal Cepat</h3>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-500 hover:text-gray-800 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('pijar.schedules.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Mata Kuliah</label>
                    <select name="course_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->kode_mk }} - {{ $course->nama_mk }} ({{ $course->prodi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Ruang Kuliah</label>
                    <select name="room_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->kode_ruang }} - {{ $room->nama_ruang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Dosen Pengajar</label>
                    <input type="text" name="dosen_pengajar" required placeholder="Nama lengkap & gelar dosen" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Peran Pengaju</label>
                        <input type="text" name="peran_pengaju" value="Admin PIJAR" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Awal</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="approved">Approved</option>
                            <option value="pending_approval">Pending Approval</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH JADWAL BARU --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Tambah Jadwal Kuliah / Praktisi</h3>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('pijar.schedules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Mata Kuliah</label>
                    <select name="course_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->kode_mk }} - {{ $c->nama_mk }} ({{ $c->prodi }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Ruang Kelas</label>
                    <select name="room_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->kode_ruang }} (Kapasitas {{ $r->kapasitas }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Nama Dosen / Praktisi Pengajar</label>
                    <input type="text" name="dosen_pengajar" required placeholder="Contoh: Budi Santoso, S.Kom., M.T." class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Peran Pengaju</label>
                        <select name="peran_pengaju" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="Kaprodi">Kaprodi</option>
                            <option value="Admin PIJAR">Admin PIJAR</option>
                            <option value="Admin SUAR">Admin SUAR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Awal</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="pending_approval">Pending Approval</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Unggah Berkas RPS / RPS Mini (PDF/PPT/DOCX)</label>
                    <input type="file" name="dokumen_bahan_ajar" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-gray-50">
                </div>
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_external" id="is_external_mj" value="1" class="rounded text-[#0A4D29]">
                    <label for="is_external_mj" class="font-semibold text-gray-700">Tandai sebagai Jadwal Pihak Eksternal / Praktisi</label>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>

        function openApprovalModal(btn) {
            const d = btn.dataset;

            document.getElementById('formDecision').action = '/pijar/schedules/' + d.id;
            document.getElementById('modal_req_id').textContent = 'ID: ' + d.reqid;
            document.getElementById('modal_judul_mk').textContent = 'Persetujuan Jadwal: ' + d.kodemk + ' - ' + d.namamk;
            document.getElementById('modal_alert_ruang').textContent = d.roomkode;
            document.getElementById('modal_prodi').textContent = d.prodi;
            document.getElementById('modal_sks').textContent = d.sks + ' SKS';
            document.getElementById('modal_kelas').textContent = d.kelas;
            document.getElementById('modal_dosen').textContent = d.dosen;
            document.getElementById('modal_pengaju').textContent = d.pengaju;
            document.getElementById('modal_waktu_ajuan').textContent = d.waktuajuan;
            document.getElementById('modal_hari_sesi').textContent = d.harisesi;
            document.getElementById('modal_waktu_sesi').textContent = d.waktusesi;
            document.getElementById('modal_ruang_info').textContent = d.roomkode;
            document.getElementById('modal_ruang_kapasitas').textContent = 'Kapasitas: ' + d.roomkapasitas + ' Kursi';
            document.getElementById('modal_catatan_pengaju').textContent = d.catatan;
            document.getElementById('input_dosen').value = d.dosen;
            document.getElementById('input_room_id').value = d.roomid;
            document.getElementById('modal_pertemuan_ke').textContent = d.pertemuanke;
            document.getElementById('modal_kuota_mk').textContent = d.kuotamk;
            document.getElementById('modal_kuota_prodi').textContent = d.kuotaprodi;

            const badge = document.getElementById('modal_badge_status');
            if (d.status === 'approved') {
                badge.className = 'bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '✓ Jadwal Sudah Disetujui';
            } else if (d.status === 'revision_requested') {
                badge.className = 'bg-[#FADBD8] text-[#C0392B] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '! Menunggu Revisi Pengaju';
            } else if (d.status === 'cancelled') {
                badge.className = 'bg-gray-200 text-gray-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '✕ Jadwal Ditolak';
            } else {
                badge.className = 'bg-[#FFF0E0] text-[#F57C00] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '● Menunggu Verifikasi Admin';
            }

            const docLink = document.getElementById('modal_link_dokumen');
                    if (d.dokumen) {
                        docLink.href = d.dokumen;
                        docLink.textContent = '📄 Buka File RPS / Bahan Ajar';
                        docLink.classList.remove('pointer-events-none', 'text-gray-400');
                        docLink.classList.add('text-[#F57C00]');
                    } else {
                        docLink.href = '#';
                        docLink.textContent = 'Belum Ada File Diunggah';
                        docLink.classList.add('pointer-events-none', 'text-gray-400');
                        docLink.classList.remove('text-[#F57C00]');
                    }
            
            document.getElementById('modalApproval').classList.remove('hidden');
        }

        function closeApprovalModal() {
            document.getElementById('modalApproval').classList.add('hidden');
        }
    </script>

</body>
</html>