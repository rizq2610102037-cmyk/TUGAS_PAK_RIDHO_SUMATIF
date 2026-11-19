<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Jadwal & Approval - Admin PIJAR & SUAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin Terpadu &rsaquo; Akademik & Praktisi</p>
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

        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

            {{-- SIDEBAR TERPADU --}}
            @include('layouts.sidebar-admin')

            {{-- KONTEN UTAMA MANAJEMEN JADWAL --}}
            <div class="flex-1 min-w-0 w-full space-y-5">

                {{-- Header, Filter Semester/Tahun Ajaran, & Tombol Aksi --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-gray-100">
                        <div>
                            <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-3 py-0.5 rounded-full mb-1">
                                Periode Akademik: {{ $tahunAjaran ?: 'Semua Tahun Ajaran' }} {{ $semester ? '• Semester ' . $semester : '' }}
                            </span>
                            <h2 class="text-2xl font-extrabold text-gray-900">Verifikasi & Validasi Jadwal Semester</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Kelola pengajuan PIJAR & SUAR berdasarkan Semester & Tahun Ajaran, kustomisasi data master, serta aktifkan akun dosen otomatis saat ACC.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            <button type="button" onclick="document.getElementById('modalMaster').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl border border-[#0A4D29] text-[#0A4D29] hover:bg-[#E8F5E9] text-xs font-bold cursor-pointer">
                                ⚙️ Master Ruang & MK
                            </button>
                            <a href="{{ route('pijar.manajemen-jadwal') }}" class="px-3.5 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">
                                ↻ Reset Filter
                            </a>
                            <button type="button" onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white text-xs font-bold shadow-sm cursor-pointer">
                                + Tambah Jadwal Semester
                            </button>
                        </div>
                    </div>

                    {{-- Filter Tahun Ajaran & Semester --}}
                    <form action="{{ route('pijar.manajemen-jadwal') }}" method="GET" class="flex flex-wrap items-center gap-2.5 pt-4 pb-3 border-b border-gray-100 text-xs">
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="search" value="{{ $search }}">

                        <select name="tahun_ajaran" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-bold text-gray-700">
                            <option value="">Semua Tahun Ajaran</option>
                            @foreach(['2026-2027', '2027-2028', '2028-2029', '2029-2030', '2030-2031'] as $th)
                                <option value="{{ $th }}" {{ ($tahunAjaran ?? '') === $th ? 'selected' : '' }}>Tahun Ajaran {{ $th }}</option>
                            @endforeach
                        </select>

                        <select name="semester_jenis" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-bold text-gray-700">
                            <option value="">Semua Semester (Ganjil/Genap)</option>
                            <option value="Ganjil" {{ ($semester ?? '') === 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                            <option value="Genap" {{ ($semester ?? '') === 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                        </select>
                    </form>

                    {{-- Tab Filter Status --}}
                    <div class="flex flex-wrap items-center gap-2 pt-4 text-xs">
                        <a href="{{ route('pijar.manajemen-jadwal', ['tahun_ajaran' => $tahunAjaran, 'semester_jenis' => $semester]) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ !$status ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Semua Jadwal ({{ $totalJadwal }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'pending_approval', 'tahun_ajaran' => $tahunAjaran, 'semester_jenis' => $semester]) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'pending_approval' ? 'bg-[#F57C00] text-white' : 'bg-[#FFF0E0] text-[#F57C00] hover:bg-[#ffe1bf]' }}">
                            Menunggu Verifikasi ({{ $menungguApprove }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'approved', 'tahun_ajaran' => $tahunAjaran, 'semester_jenis' => $semester]) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'approved' ? 'bg-[#0A4D29] text-white' : 'bg-[#D5F5E3] text-[#0A4D29] hover:bg-[#bcefd2]' }}">
                            Disetujui / ACC ({{ $jadwalDisetujui }})
                        </a>
                        <a href="{{ route('pijar.manajemen-jadwal', ['status' => 'revision_requested', 'tahun_ajaran' => $tahunAjaran, 'semester_jenis' => $semester]) }}" class="px-3.5 py-1.5 rounded-full font-bold transition {{ $status === 'revision_requested' ? 'bg-[#FF0000] text-white' : 'bg-[#FADBD8] text-[#C0392B] hover:bg-[#f5c6c2]' }}">
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
                                    <th class="py-3.5 px-4">Pengampu & Akun Kampus</th>
                                    <th class="py-3.5 px-4">Semester & Ruangan</th>
                                    <th class="py-3.5 px-4 text-center">Status Verifikasi</th>
                                    <th class="py-3.5 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-xs">
                                @forelse ($schedules as $item)
                                    @php
                                        $reqId = 'REQ-' . ($item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('Ymd') : date('Ymd')) . '-' . str_pad($item->id, 3, '0', STR_PAD_LEFT);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-1.5 mb-0.5">
                                                <span class="text-[10px] font-mono font-bold text-gray-400">{{ $reqId }}</span>
                                                @if($item->is_external || $item->jenis_program === 'SUAR')
                                                    <span class="bg-[#FFF0E0] text-[#F57C00] text-[9px] font-extrabold px-1.5 py-0.5 rounded">SUAR</span>
                                                @else
                                                    <span class="bg-[#E8F5E9] text-[#0A4D29] text-[9px] font-extrabold px-1.5 py-0.5 rounded">PIJAR</span>
                                                @endif
                                            </div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $item->course->kode_mk }} - {{ $item->course->nama_mk }}</p>
                                            <p class="text-gray-500 mt-0.5">{{ $item->course->sks }} SKS · {{ $item->kelas ?? 'Kelas A' }}</p>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-800">
                                            {{ $item->course->prodi }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-900 whitespace-pre-line">{{ $item->praktisi_nama ?: $item->dosen_pengajar }}</p>
                                            <p class="text-[11px] text-gray-500 mt-0.5">Pengaju: {{ $item->peran_pengaju ?? 'Kaprodi' }}</p>
                                            @if($item->dosen_email_kampus)
                                                <p class="text-[10px] text-[#0A4D29] font-semibold mt-0.5">✉️ {{ $item->dosen_email_kampus }}</p>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-800">TA {{ $item->tahun_ajaran ?? '2026-2027' }}</p>
                                            <p class="text-gray-600">Semester {{ $item->semester_jenis ?? 'Ganjil' }} (Sem. {{ $item->semester_angka ?? 1 }})</p>
                                            <span class="inline-block mt-1 bg-[#E8F5E9] text-[#0A4D29] font-bold px-2 py-0.5 rounded text-[11px]">
                                                {{ $item->room->kode_ruang }} (Kapasitas: {{ $item->room->kapasitas }})
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($item->status === 'pending_approval' || $item->status === 'pending')
                                                <span class="inline-block bg-[#FFF0E0] text-[#F57C00] border border-[#F57C00]/30 font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ● Menunggu Verifikasi
                                                </span>
                                            @elseif ($item->status === 'approved')
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3.5 py-1 rounded-full text-[11px]">
                                                    ✓ Disetujui (ACC)
                                                </span>
                                            @elseif ($item->status === 'revision_requested')
                                                <span class="inline-block bg-[#FADBD8] text-[#C0392B] font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ! Minta Revisi
                                                </span>
                                            @else
                                                <span class="inline-block bg-red-100 text-red-700 font-bold px-3 py-1 rounded-full text-[11px]">
                                                    ✕ Ditolak
                                                </span>
                                                @if($item->alasan_penolakan)
                                                    <p class="text-[10px] text-red-600 mt-1 max-w-[160px] mx-auto">{{ $item->alasan_penolakan }}</p>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button
                                                type="button"
                                                onclick="openApprovalModal(this)"
                                                data-id="{{ $item->id }}"
                                                data-reqid="{{ $reqId }}"
                                                data-kodemk="{{ $item->course->kode_mk ?? '' }}"
                                                data-namamk="{{ $item->course->nama_mk ?? '' }}"
                                                data-prodi="{{ $item->course->prodi ?? '' }}"
                                                data-sks="{{ $item->course->sks ?? 3 }}"
                                                data-kelas="{{ $item->kelas ?? 'Kelas A' }}"
                                                data-dosen="{{ $item->praktisi_nama ?: $item->dosen_pengajar }}"
                                                data-pengaju="{{ $item->peran_pengaju ?? 'Kaprodi' }}"
                                                data-waktuajuan="{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}"
                                                data-tahunajaran="{{ $item->tahun_ajaran ?? '2026-2027' }}"
                                                data-semesterjenis="{{ $item->semester_jenis ?? 'Ganjil' }}"
                                                data-semesterangka="{{ $item->semester_angka ?? 1 }}"
                                                data-roomid="{{ $item->room_id }}"
                                                data-roomkode="{{ $item->room->kode_ruang ?? '-' }}"
                                                data-roomkapasitas="{{ $item->room->kapasitas ?? 40 }}"
                                                data-pertemuanke="{{ $item->pertemuan_ke ?? 1 }}"
                                                data-kuotamk="{{ $item->kuota_mk_terpakai ?? 0 }}"
                                                data-kuotaprodi="{{ $item->kuota_prodi_terpakai ?? 0 }}"
                                                data-email="{{ $item->dosen_email_kampus ?? ($item->dosen_email ?? '') }}"
                                                data-alasan="{{ $item->alasan_penolakan ?? '' }}"
                                                data-status="{{ $item->status }}"
                                                data-dokumen="{{ $item->dokumen_bahan_ajar ? asset('storage/' . $item->dokumen_bahan_ajar) : '' }}"
                                                class="px-3.5 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-[11px] cursor-pointer shadow-xs"
                                            >
                                                Periksa &amp; Putuskan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            Belum ada data jadwal sesuai filter semester/tahun ajaran yang dipilih.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        {{ $schedules->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL PERSETUJUAN JADWAL --}}
    <div id="modalApproval" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-3xl w-full p-6 md:p-7 shadow-2xl my-8 relative">

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
                        <p class="text-xs text-gray-500 mt-0.5">Verifikasi mata kuliah, beban SKS, semester aktif, dan aktivasi akun email kampus pengajar.</p>
                    </div>
                </div>

                <button type="button" onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-700 p-1 text-lg font-bold cursor-pointer">✕</button>
            </div>

            {{-- Grid 2 Kolom: Spesifikasi Akademik & Pihak Pengampu --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
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
                        <span class="text-gray-500">Kelas</span>
                        <span id="modal_kelas" class="font-bold text-gray-900">Kelas A</span>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-2.5 text-xs">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">PIHAK PENGAMPU & PENGUSUL</p>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Dosen / Praktisi</span>
                        <span id="modal_dosen" class="font-bold text-gray-900 text-right whitespace-pre-line">Nama Dosen</span>
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

            {{-- Periode Semester & Alokasi Ruang --}}
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 mt-4">
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2.5">PERIODE SEMESTER & ALOKASI RUANG</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Tahun Ajaran</p>
                        <p id="modal_tahun_ajaran" class="font-extrabold text-gray-900 text-sm mt-0.5">2026-2027</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Kurikulum Aktif</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Semester</p>
                        <p id="modal_semester_info" class="font-extrabold text-gray-900 text-sm mt-0.5">Ganjil (Semester 1)</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Sesuai Plotting Prodi</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-3">
                        <p class="text-[11px] text-gray-500">Ruang & Kapasitas</p>
                        <p id="modal_ruang_info" class="font-extrabold text-gray-900 text-sm mt-0.5">Smart Class</p>
                        <p id="modal_ruang_kapasitas" class="text-[10px] font-bold text-[#0A4D29] mt-0.5">Kapasitas: 45 Kursi</p>
                    </div>
                </div>
            </div>

            {{-- Blok Kepatuhan SOP PRU/SPMI/SD-BM10 --}}
            <div class="bg-[#FAFCEB] border border-[#0A4D29]/20 rounded-xl p-4 mt-4 text-xs space-y-2.5">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#0A4D29]">KONTROL KUOTA & DOKUMEN SOP (SD-BM-10)</p>
                    <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-2 py-0.5 rounded text-[10px]">Pertemuan ke-<span id="modal_pertemuan_ke">1</span></span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                        <p class="text-[10px] text-gray-500">Kuota MK (Maks 7x)</p>
                        <p class="font-extrabold text-gray-900 mt-0.5"><span id="modal_kuota_mk">0</span> / 7 Pertemuan</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                        <p class="text-[10px] text-gray-500">Kuota Prodi/Tahun (Maks 28x)</p>
                        <p class="font-extrabold text-[#0A4D29] mt-0.5"><span id="modal_kuota_prodi">0</span> / 28 Pertemuan</p>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-lg p-2.5">
                        <p class="text-[10px] text-gray-500">Berkas RPS / Silabus</p>
                        <a id="modal_link_dokumen" href="#" target="_blank" class="font-bold text-[#F57C00] hover:underline mt-0.5 inline-block">
                            📄 Unduh / Lihat Berkas
                        </a>
                    </div>
                </div>
            </div>

            {{-- Form Keputusan Administrator --}}
            <form id="formDecision" method="POST" class="mt-5 pt-4 border-t border-gray-200 space-y-3 text-xs">
                @csrf
                @method('PUT')
                <input type="hidden" name="dosen_pengajar" id="input_dosen">
                <input type="hidden" name="status" id="input_status" value="approved">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-600 mb-1">Alokasi Ruang Kuliah:</label>
                        <select name="room_id" id="input_room_id" class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_ruang }} - {{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-gray-600 mb-1">Email Kampus Pengajar (Otomatis aktifkan akun saat ACC):</label>
                        <input type="email" name="dosen_email_kampus" id="input_email_kampus" placeholder="Kosongkan untuk generate otomatis (@pradita.ac.id)" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-600 mb-1">Catatan Admin / Alasan Penolakan (Jika Ditolak):</label>
                    <input type="text" name="alasan_penolakan" id="input_alasan_penolakan" placeholder="Isi alasan penolakan apabila memilih Tolak Jadwal (ditampilkan ke Kaprodi)..." class="w-full border border-gray-300 rounded-xl px-3.5 py-2 focus:outline-none focus:ring-2 focus:ring-[#0A4D29]">
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="submit" onclick="document.getElementById('input_status').value='approved'" class="px-4 py-2 rounded-xl bg-[#D5F5E3] hover:bg-[#0A4D29] text-[#0A4D29] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✓ ACC & Aktifkan Akun Pengajar
                        </button>
                        <button type="submit" onclick="document.getElementById('input_status').value='revision_requested'" class="px-4 py-2 rounded-xl bg-[#FFF0E0] hover:bg-[#F57C00] text-[#F57C00] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✎ Minta Revisi
                        </button>
                        <button type="submit" onclick="document.getElementById('input_status').value='cancelled'" class="px-4 py-2 rounded-xl bg-[#FADBD8] hover:bg-[#C0392B] text-[#C0392B] hover:text-white font-extrabold text-xs transition cursor-pointer">
                            ✕ Tolak Jadwal
                        </button>
                    </div>

                    <button type="button" onclick="closeApprovalModal()" class="px-4 py-2 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-bold text-xs cursor-pointer">
                        Tutup
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- MODAL TAMBAH JADWAL SEMESTER (JURUSAN PRADITA -> MATA KULIAH TERFILTER + TAMBAH RUANG INLINE) --}}
    <div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl my-8">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Tambah Jadwal Kuliah / Praktisi</h3>
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>

            @php
                $prodiPradita = collect([
                    'Sistem Informasi',
                    'Teknik Informatika',
                    'Arsitektur',
                    'Perencanaan Wilayah dan Kota',
                    'Desain Komunikasi Visual',
                    'Desain Interior',
                    'Manajemen',
                    'Akuntansi',
                    'Pariwisata',
                    'Seni Kuliner',
                    'Manajemen Bisnis Retail',
                    'Teknologi Pangan',
                ])->merge($courses->pluck('prodi')->filter())->unique()->values();
            @endphp

            <form action="{{ route('pijar.schedules.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                @csrf

                {{-- Pilih Jurusan / Program Studi Pradita --}}
                <div>
                    <label class="block font-bold mb-1">Program Studi / Jurusan (Universitas Pradita)</label>
                    <select id="filter_prodi_modal" onchange="filterCoursesByProdi()" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 font-semibold text-gray-800">
                        <option value="">-- Semua Program Studi Pradita --</option>
                        @foreach($prodiPradita as $prodiName)
                            <option value="{{ $prodiName }}">{{ $prodiName }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Mata Kuliah (Otomatis Tersaring Berdasarkan Jurusan di Atas) --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-bold">Mata Kuliah</label>
                        <span id="course_count_badge" class="text-[10px] font-bold text-[#0A4D29]">{{ $courses->count() }} MK tersedia</span>
                    </div>
                    <select name="course_id" id="select_course_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" data-prodi="{{ $c->prodi }}">
                                {{ $c->kode_mk }} - {{ $c->nama_mk }} ({{ $c->prodi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilihan Tahun Ajaran & Semester --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold mb-1">Tahun Ajaran</label>
                        <select name="tahun_ajaran" required class="w-full border border-gray-300 rounded-lg px-2.5 py-2">
                            @foreach(['2026-2027', '2027-2028', '2028-2029', '2029-2030', '2030-2031'] as $th)
                                <option value="{{ $th }}">{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Jenis Semester</label>
                        <select name="semester_jenis" required class="w-full border border-gray-300 rounded-lg px-2.5 py-2">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Semester</label>
                        <select name="semester_angka" required class="w-full border border-gray-300 rounded-lg px-2.5 py-2">
                            @for($s = 1; $s <= 8; $s++)
                                <option value="{{ $s }}">Sem. {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Ruang Kelas + Tombol Cepat + Tambah Ruang Baru --}}
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-bold">Ruang Kelas</label>
                        <button type="button" onclick="toggleQuickAddRoom()" class="text-[11px] font-extrabold text-[#F57C00] hover:underline cursor-pointer">
                            + Tambah Ruang Baru
                        </button>
                    </div>

                    <select name="room_id" id="select_room_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}">{{ $r->kode_ruang }} - {{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})</option>
                        @endforeach
                    </select>

                    {{-- Panel Input Tambah Ruang Cepat (Inline) --}}
                    <div id="quickAddRoomBox" class="hidden mt-2.5 p-3 rounded-xl bg-[#FAFCEB] border border-[#0A4D29]/25 space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] font-extrabold text-[#0A4D29]">Tambah Data Ruang Kelas Baru</span>
                            <button type="button" onclick="toggleQuickAddRoom()" class="text-xs text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <input type="text" id="new_kode_ruang" placeholder="Kode (cth: R-402)" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                            <input type="text" id="new_nama_ruang" placeholder="Nama (cth: Lab Komputer)" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                            <input type="number" id="new_kapasitas_ruang" value="40" min="1" placeholder="Kapasitas" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                        </div>

                        <p id="quickRoomError" class="hidden text-[11px] text-red-600 font-bold"></p>

                        <div class="flex justify-end">
                            <button type="button" onclick="submitQuickRoom()" id="btnSaveQuickRoom" class="px-3.5 py-1.5 rounded-lg bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-[11px] cursor-pointer">
                                ✓ Simpan & Pilih Ruang Ini
                            </button>
                        </div>
                    </div>
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
                            <option value="Admin PIJAR & SUAR">Admin PIJAR & SUAR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Awal</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="pending_approval">Pending Approval</option>
                            <option value="approved">Approved (Auto-Akun)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold mb-1">Unggah Berkas RPS / Silabus (PDF/PPT/DOCX)</label>
                    <input type="file" name="dokumen_bahan_ajar" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-gray-50">
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_external" id="is_external_mj" value="1" class="rounded text-[#0A4D29]">
                    <label for="is_external_mj" class="font-semibold text-gray-700">Tandai sebagai Program SUAR (Praktisi Industri)</label>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KUSTOMISASI DATA MASTER RUANGAN & MATA KULIAH --}}
    <div id="modalMaster" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-[#0A4D29]">Kustomisasi Data Master (Ruangan & Mata Kuliah)</h3>
                    <p class="text-xs text-gray-500">Data yang ditambahkan di sini akan muncul otomatis di pilihan dropdown Kaprodi.</p>
                </div>
                <button type="button" onclick="document.getElementById('modalMaster').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <form action="{{ route('pijar.master.room') }}" method="POST" class="space-y-3 bg-gray-50 p-4 rounded-xl border">
                    @csrf
                    <h4 class="font-extrabold text-sm text-gray-900">+ Tambah Ruang Kelas Baru</h4>
                    <div>
                        <label class="block font-bold mb-1">Kode Ruang</label>
                        <input type="text" name="kode_ruang" required placeholder="Contoh: R-304 / Smart Class 2" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Nama / Gedung Ruangan</label>
                        <input type="text" name="nama_ruang" required placeholder="Contoh: Gedung Utama Lt. 3" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Kapasitas Kursi</label>
                        <input type="number" name="kapasitas" value="40" min="5" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Ruangan</button>
                </form>

                <form action="{{ route('pijar.master.course') }}" method="POST" class="space-y-3 bg-gray-50 p-4 rounded-xl border">
                    @csrf
                    <h4 class="font-extrabold text-sm text-gray-900">+ Tambah Mata Kuliah Kurikulum</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold mb-1">Kode MK</label>
                            <input type="text" name="kode_mk" required placeholder="IS4012" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Bobot SKS</label>
                            <input type="number" name="sks" value="3" min="1" max="6" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        </div>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Nama Mata Kuliah</label>
                        <input type="text" name="nama_mk" required placeholder="Contoh: Arsitektur Enterprise" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block font-bold mb-1">Program Studi</label>
                            <input type="text" name="prodi" required placeholder="Sistem Informasi" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        </div>
                        <div>
                            <label class="block font-bold mb-1">Semester MK</label>
                            <select name="semester" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                                @for($s = 1; $s <= 8; $s++)
                                    <option value="{{ $s }}">Semester {{ $s }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-[#F57C00] text-white font-bold cursor-pointer">Simpan Mata Kuliah</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function filterCoursesByProdi() {
            const selectedProdi = document.getElementById('filter_prodi_modal').value.toLowerCase();
            const courseSelect = document.getElementById('select_course_id');
            const badge = document.getElementById('course_count_badge');
            const options = Array.from(courseSelect.options);

            let visibleCount = 0;
            let firstVisibleOption = null;

            options.forEach(opt => {
                const optProdi = (opt.dataset.prodi || '').toLowerCase();
                const isMatch = !selectedProdi || optProdi === selectedProdi;

                opt.hidden = !isMatch;
                opt.disabled = !isMatch;

                if (isMatch) {
                    visibleCount++;
                    if (!firstVisibleOption) {
                        firstVisibleOption = opt;
                    }
                }
            });

            if (firstVisibleOption) {
                courseSelect.value = firstVisibleOption.value;
            } else {
                courseSelect.value = '';
            }

            if (badge) {
                badge.textContent = visibleCount > 0
                    ? visibleCount + ' MK tersedia'
                    : 'Belum ada MK di prodi ini (tambah di Master MK)';
            }
        }

        function openApprovalModal(btn) {
            const d = btn.dataset;

            document.getElementById('formDecision').action = '/pijar/schedules/' + d.id;
            document.getElementById('modal_req_id').textContent = 'ID: ' + d.reqid;
            document.getElementById('modal_judul_mk').textContent = 'Persetujuan Jadwal: ' + d.kodemk + ' - ' + d.namamk;
            document.getElementById('modal_prodi').textContent = d.prodi;
            document.getElementById('modal_sks').textContent = d.sks + ' SKS';
            document.getElementById('modal_kelas').textContent = d.kelas;
            document.getElementById('modal_dosen').textContent = d.dosen;
            document.getElementById('modal_pengaju').textContent = d.pengaju;
            document.getElementById('modal_waktu_ajuan').textContent = d.waktuajuan;
            document.getElementById('modal_tahun_ajaran').textContent = d.tahunajaran;
            document.getElementById('modal_semester_info').textContent = d.semesterjenis + ' (Semester ' + d.semesterangka + ')';
            document.getElementById('modal_ruang_info').textContent = d.roomkode;
            document.getElementById('modal_ruang_kapasitas').textContent = 'Kapasitas: ' + d.roomkapasitas + ' Kursi';
            document.getElementById('input_dosen').value = d.dosen;
            document.getElementById('input_room_id').value = d.roomid;
            document.getElementById('input_email_kampus').value = d.email;
            document.getElementById('input_alasan_penolakan').value = d.alasan;
            document.getElementById('modal_pertemuan_ke').textContent = d.pertemuanke;
            document.getElementById('modal_kuota_mk').textContent = d.kuotamk;
            document.getElementById('modal_kuota_prodi').textContent = d.kuotaprodi;

            const badge = document.getElementById('modal_badge_status');
            if (d.status === 'approved') {
                badge.className = 'bg-[#D5F5E3] text-[#0A4D29] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '✓ Jadwal Sudah Disetujui (ACC)';
            } else if (d.status === 'revision_requested') {
                badge.className = 'bg-[#FADBD8] text-[#C0392B] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '! Menunggu Revisi Pengaju';
            } else if (d.status === 'cancelled') {
                badge.className = 'bg-red-100 text-red-700 text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '✕ Jadwal Ditolak';
            } else {
                badge.className = 'bg-[#FFF0E0] text-[#F57C00] text-[11px] font-bold px-2.5 py-0.5 rounded-full';
                badge.textContent = '● Menunggu Verifikasi Admin';
            }

            const docLink = document.getElementById('modal_link_dokumen');
            if (d.dokumen) {
                docLink.href = d.dokumen;
                docLink.textContent = '📄 Buka File RPS / Silabus';
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

        function toggleQuickAddRoom() {
            const box = document.getElementById('quickAddRoomBox');
            const err = document.getElementById('quickRoomError');
            err.classList.add('hidden');
            box.classList.toggle('hidden');
        }

        async function submitQuickRoom() {
            const kode = document.getElementById('new_kode_ruang').value.trim();
            const nama = document.getElementById('new_nama_ruang').value.trim();
            const kapasitas = document.getElementById('new_kapasitas_ruang').value;
            const errEl = document.getElementById('quickRoomError');
            const btn = document.getElementById('btnSaveQuickRoom');

            if (!kode || !nama || !kapasitas) {
                errEl.textContent = 'Kode ruang, nama ruang, dan kapasitas wajib diisi.';
                errEl.classList.remove('hidden');
                return;
            }

            errEl.classList.add('hidden');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            try {
                const res = await fetch("{{ route('pijar.master.room') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        kode_ruang: kode,
                        nama_ruang: nama,
                        kapasitas: parseInt(kapasitas, 10)
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    const firstErr = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Gagal menambah ruang.');
                    errEl.textContent = firstErr;
                    errEl.classList.remove('hidden');
                } else {
                    const label = `${data.room.kode_ruang} - ${data.room.nama_ruang} (Kapasitas ${data.room.kapasitas})`;
                    ['select_room_id', 'input_room_id'].forEach(selId => {
                        const select = document.getElementById(selId);
                        if (select) {
                            const option = document.createElement('option');
                            option.value = data.room.id;
                            option.textContent = label;
                            if (selId === 'select_room_id') option.selected = true;
                            select.appendChild(option);
                        }
                    });

                    document.getElementById('new_kode_ruang').value = '';
                    document.getElementById('new_nama_ruang').value = '';
                    document.getElementById('new_kapasitas_ruang').value = '40';
                    document.getElementById('quickAddRoomBox').classList.add('hidden');
                }
            } catch (e) {
                errEl.textContent = 'Terjadi kesalahan koneksi saat menyimpan ruang.';
                errEl.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = '✓ Simpan & Pilih Ruang Ini';
            }
        }
    </script>
</body>
</html>