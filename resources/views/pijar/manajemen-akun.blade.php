<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun & Impor Master - Admin PIJAR & SUAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin Terpadu &rsaquo; Otorisasi & Data Master</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Manajemen Akun & Impor Master</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('pijar.manajemen-akun') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, email, username, atau NIP pengguna..." 
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

        @if ($errors->any())
            <div class="bg-[#FADBD8] border border-[#C0392B] text-[#C0392B] px-4 py-3 rounded-xl text-xs font-bold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 items-start w-full">

            {{-- SIDEBAR TERPADU --}}
            @include('layouts.sidebar-admin')

            {{-- KONTEN UTAMA KANAN --}}
            <div class="flex-1 min-w-0 w-full space-y-5">

                {{-- HEADER HALAMAN + TOMBOL IMPOR EXCEL, EKSPOR & TAMBAH AKUN --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider uppercase text-[#0A4D29]">OTORISASI & DATA MASTER INSTITUSIONAL</p>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mt-0.5">Manajemen Akun & Impor Excel</h2>
                        <p class="text-xs text-gray-600 mt-1">Kelola otorisasi Admin Terpadu, Kaprodi, Dosen, & Praktisi, serta impor data master secara massal lewat template Excel/CSV.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                        <button type="button" onclick="document.getElementById('modalImporExcel').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-[#F57C00] hover:bg-[#e07000] text-white font-bold text-xs shadow-xs cursor-pointer">
                            📥 Impor Excel (Dosen & MK)
                        </button>
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-bold text-xs shadow-xs cursor-pointer">
                            ⬇ Ekspor Data
                        </button>
                        <button type="button" onclick="document.getElementById('modalTambahUser').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs cursor-pointer">
                            + Tambah Akun Baru
                        </button>
                    </div>
                </div>

                {{-- 4 KARTU METRIK PENGGUNA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase">TOTAL PENGGUNA</p>
                            <span class="w-9 h-9 rounded-xl bg-[#E8F5E9] text-[#0A4D29] flex items-center justify-center text-sm font-bold">👥</span>
                        </div>
                        <div class="my-3 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $totalPengguna }}</span>
                            <span class="text-xs text-gray-500 font-medium">Akun Terdaftar</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] pt-2 border-t border-gray-100">
                            <span class="text-gray-500">Kapasitas Portal: <strong>{{ $persenAktif }}% Aktif</strong></span>
                            <span class="w-2 h-2 rounded-full bg-[#0A4D29]"></span>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase">AKUN KAPRODI</p>
                            <span class="w-9 h-9 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center text-sm">🎓</span>
                        </div>
                        <div class="my-3 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $totalKaprodi }}</span>
                            <span class="text-xs text-gray-500 font-medium">Ketua & Sekprodi</span>
                        </div>
                        <p class="text-[11px] text-gray-500 pt-2 border-t border-gray-100">
                            <strong class="text-gray-800">10 Prodi S1</strong> terpetakan penuh
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase">ADMIN TERPADU</p>
                            <span class="w-9 h-9 rounded-xl bg-[#F57C00] text-white flex items-center justify-center text-sm">🔬</span>
                        </div>
                        <div class="my-3 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $totalSuar }}</span>
                            <span class="text-xs text-gray-500 font-medium">PIJAR & SUAR</span>
                        </div>
                        <p class="text-[11px] text-gray-500 pt-2 border-t border-gray-100">
                            <strong class="text-[#F57C00]">Terintegrasi</strong> 1 portal pusat
                        </p>
                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase">BIRO KERMA</p>
                            <span class="w-9 h-9 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center text-sm">🤝</span>
                        </div>
                        <div class="my-3 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $totalKerma }}</span>
                            <span class="text-xs text-gray-500 font-medium">Kemitraan Industri</span>
                        </div>
                        <p class="text-[11px] text-gray-500 pt-2 border-t border-gray-100">
                            <strong class="text-[#0A4D29]">100%</strong> sinkron ke jadwal praktisi
                        </p>
                    </div>
                </div>

                {{-- FILTER BAR --}}
                <form action="{{ route('pijar.manajemen-akun') }}" method="GET" class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-wrap items-center gap-3 text-xs">
                    <div class="relative flex-1 min-w-[220px]">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">🔍</span>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, email, username, atau NIP pengguna..." class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-200 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#0A4D29]">
                    </div>

                    <select name="role" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin Terpadu</option>
                        <option value="kaprodi" {{ $role === 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                        <option value="dosen" {{ $role === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        <option value="praktisi" {{ $role === 'praktisi' ? 'selected' : '' }}>Praktisi Industri</option>
                        <option value="admin_kerma" {{ $role === 'admin_kerma' ? 'selected' : '' }}>KERMA</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>

                    <select name="prodi" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium">
                        <option value="">Semua Program Studi / Unit</option>
                        @foreach($listProdi as $p)
                            <option value="{{ $p }}" {{ $prodi === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('pijar.manajemen-akun') }}" title="Reset Filter" class="px-3 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold">
                        ↻ Reset
                    </a>
                </form>

                {{-- TABEL DAFTAR AKUN PENGGUNA --}}
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4 w-12">NO</th>
                                    <th class="py-3.5 px-4">NAMA LENGKAP & PROFIL</th>
                                    <th class="py-3.5 px-4">USERNAME / NIP</th>
                                    <th class="py-3.5 px-4">EMAIL KAMPUS</th>
                                    <th class="py-3.5 px-4 text-center">ROLE</th>
                                    <th class="py-3.5 px-4">PROGRAM STUDI / UNIT KERJA</th>
                                    <th class="py-3.5 px-4 text-center">STATUS</th>
                                    <th class="py-3.5 px-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($users as $index => $u)
                                    @php
                                        $words = explode(' ', trim($u->name));
                                        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . substr($words[1] ?? '', 0, 1));
                                    @endphp
                                    <tr class="hover:bg-[#FAFCEB] transition">
                                        <td class="py-4 px-4 font-bold text-gray-400">
                                            {{ str_pad($users->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-[#0A4D29] text-white font-extrabold text-xs flex items-center justify-center shrink-0">
                                                    {{ $initials }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-900">{{ $u->name }}</p>
                                                    <p class="text-[11px] text-gray-500">NIDN/NIP: {{ $u->nip_nidn ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-700">
                                            {{ $u->username ?? explode('@', $u->email)[0] }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="mailto:{{ $u->email }}" class="text-[#0A4D29] hover:underline font-medium">{{ $u->email }}</a>
                                            @if($u->must_change_password)
                                                <span class="block text-[10px] text-[#F57C00] font-semibold">Wajib ganti sandi awal</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($u->role === 'kaprodi')
                                                <span class="inline-block bg-[#0A4D29] text-white font-bold px-3 py-1 rounded-full text-[11px]">• Kaprodi</span>
                                            @elseif ($u->role === 'dosen')
                                                <span class="inline-block bg-[#E8F5E9] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">• Dosen</span>
                                            @elseif ($u->role === 'praktisi')
                                                <span class="inline-block bg-[#FFF0E0] text-[#F57C00] font-bold px-3 py-1 rounded-full text-[11px]">• Praktisi</span>
                                            @elseif ($u->role === 'admin_kerma')
                                                <span class="inline-block bg-[#117A65] text-white font-bold px-3 py-1 rounded-full text-[11px]">• KERMA</span>
                                            @else
                                                <span class="inline-block bg-gray-800 text-white font-bold px-3 py-1 rounded-full text-[11px]">• Admin</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 font-medium text-gray-800">
                                            {{ $u->prodi ? 'S1 ' . $u->prodi : 'Pusat Administrasi PIJAR & SUAR' }}
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($u->is_active)
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-0.5 rounded-full text-[11px]">• Aktif</span>
                                            @else
                                                <span class="inline-block bg-gray-200 text-gray-600 font-bold px-3 py-0.5 rounded-full text-[11px]">• Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button 
                                                type="button"
                                                onclick="openEditUserModal(this)"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-username="{{ $u->username ?? explode('@', $u->email)[0] }}"
                                                data-nip="{{ $u->nip_nidn }}"
                                                data-role="{{ $u->role }}"
                                                data-prodi="{{ $u->prodi }}"
                                                data-active="{{ $u->is_active ? '1' : '0' }}"
                                                class="px-3 py-1 rounded-lg bg-[#E8F5E9] hover:bg-[#0A4D29] text-[#0A4D29] hover:text-white font-bold text-[11px] transition cursor-pointer">
                                                Kelola
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-10 text-center text-gray-500">Tidak ada akun pengguna ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL IMPOR EXCEL / CSV (UNDUH TEMPLATE -> ISI -> IMPOR) --}}
    <div id="modalImporExcel" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-[#0A4D29]">Impor Data Master via Excel / CSV</h3>
                    <p class="text-xs text-gray-500">Alur: 1. Unduh Template &rarr; 2. Isi data di Excel &rarr; 3. Simpan sebagai CSV & Unggah.</p>
                </div>
                <button type="button" onclick="document.getElementById('modalImporExcel').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs">
                {{-- Kolom 1: Impor Dosen, Kaprodi, & Peran --}}
                <form action="{{ route('pijar.import.users') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border space-y-3 flex flex-col justify-between">
                    @csrf
                    <div class="space-y-2.5">
                        <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-extrabold px-2.5 py-0.5 rounded-full text-[10px]">MASTER AKUN & PERAN</span>
                        <h4 class="font-extrabold text-sm text-gray-900">Impor Data Dosen, Kaprodi, & Peran</h4>
                        <p class="text-[11px] text-gray-500">Kolom template: <code>nama_lengkap, email_kampus, nidn_nip, role, prodi, password_awal</code></p>
                        <a href="{{ route('pijar.import.template-users') }}" class="inline-flex items-center gap-1.5 text-[#0A4D29] font-bold underline">
                            📄 1. Unduh Template Excel/CSV Dosen
                        </a>
                        <div class="pt-1">
                            <label class="block font-bold mb-1">2. Pilih File CSV Hasil Pengisian:</label>
                            <input type="file" name="file_csv" accept=".csv,.txt" required class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold cursor-pointer">
                        ⬆ 3. Impor Akun Dosen & Peran
                    </button>
                </form>

                {{-- Kolom 2: Impor Master Mata Kuliah --}}
                <form action="{{ route('pijar.import.courses') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-4 rounded-xl border space-y-3 flex flex-col justify-between">
                    @csrf
                    <div class="space-y-2.5">
                        <span class="inline-block bg-[#FFF0E0] text-[#F57C00] font-extrabold px-2.5 py-0.5 rounded-full text-[10px]">MASTER KURIKULUM</span>
                        <h4 class="font-extrabold text-sm text-gray-900">Impor Data Master Mata Kuliah</h4>
                        <p class="text-[11px] text-gray-500">Kolom template: <code>kode_mk, nama_mk, prodi, sks, semester</code></p>
                        <a href="{{ route('pijar.import.template-courses') }}" class="inline-flex items-center gap-1.5 text-[#F57C00] font-bold underline">
                            📄 1. Unduh Template Excel/CSV Mata Kuliah
                        </a>
                        <div class="pt-1">
                            <label class="block font-bold mb-1">2. Pilih File CSV Hasil Pengisian:</label>
                            <input type="file" name="file_csv" accept=".csv,.txt" required class="w-full border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-[#F57C00] hover:bg-[#e07000] text-white font-bold cursor-pointer">
                        ⬆ 3. Impor Master Mata Kuliah
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH AKUN BARU --}}
    <div id="modalTambahUser" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Tambah Akun Pengguna Baru</h3>
                <button type="button" onclick="document.getElementById('modalTambahUser').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('pijar.users.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" required placeholder="Contoh: Dr. Hendra Wijaya, M.T." class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Username</label>
                        <input type="text" name="username" placeholder="hendra.ti" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">NIDN / NIP</label>
                        <input type="text" name="nip_nidn" placeholder="0412088101" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Email Kampus</label>
                    <input type="email" name="email" required placeholder="nama@pradita.ac.id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Role Akses</label>
                        <select name="role" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="admin">Admin Terpadu (PIJAR & SUAR)</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="dosen">Dosen</option>
                            <option value="praktisi">Praktisi Industri</option>
                            <option value="admin_kerma">Biro KERMA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Program Studi</label>
                        <select name="prodi" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="">-- Unit Non-Prodi --</option>
                            <option value="Architecture">Architecture</option>
                            <option value="Urban Planning">Urban Planning</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Interior Design">Interior Design</option>
                            <option value="Visual Communication Design">Visual Communication Design</option>
                            <option value="Accounting">Accounting</option>
                            <option value="Management">Management</option>
                            <option value="Informatics">Informatics</option>
                            <option value="Business Information System">Business Information System</option>
                            <option value="Hospitality & Tourism">Hospitality & Tourism</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Password Awal</label>
                    <input type="password" name="password" value="Pradita2026!" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambahUser').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT OTORISASI AKUN --}}
    <div id="modalEditUser" class="hidden fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Kelola Otorisasi Pengguna</h3>
                <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>
            <form id="formEditUser" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" id="edit_user_name" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Username</label>
                        <input type="text" name="username" id="edit_user_username" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">NIDN / NIP</label>
                        <input type="text" name="nip_nidn" id="edit_user_nip" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Role Akses</label>
                        <select name="role" id="edit_user_role" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="admin">Admin Terpadu</option>
                            <option value="kaprodi">Kaprodi</option>
                            <option value="dosen">Dosen</option>
                            <option value="praktisi">Praktisi Industri</option>
                            <option value="admin_kerma">Biro KERMA</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Status Akun</label>
                        <select name="is_active" id="edit_user_active" class="w-full border border-gray-300 rounded-lg px-3 py-2 font-bold">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif (Tangguhkan)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Program Studi</label>
                    <select name="prodi" id="edit_user_prodi" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">-- Unit Non-Prodi --</option>
                        <option value="Architecture">Architecture</option>
                        <option value="Urban Planning">Urban Planning</option>
                        <option value="Civil Engineering">Civil Engineering</option>
                        <option value="Interior Design">Interior Design</option>
                        <option value="Visual Communication Design">Visual Communication Design</option>
                        <option value="Accounting">Accounting</option>
                        <option value="Management">Management</option>
                        <option value="Informatics">Informatics</option>
                        <option value="Business Information System">Business Information System</option>
                        <option value="Hospitality & Tourism">Hospitality & Tourism</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold mb-1">Reset Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')" class="px-4 py-2 rounded-lg bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-[#F57C00] text-white font-bold cursor-pointer">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditUserModal(btn) {
            const d = btn.dataset;
            document.getElementById('formEditUser').action = '/pijar/manajemen-akun/' + d.id;
            document.getElementById('edit_user_name').value = d.name;
            document.getElementById('edit_user_username').value = d.username;
            document.getElementById('edit_user_nip').value = d.nip;
            document.getElementById('edit_user_role').value = d.role;
            document.getElementById('edit_user_prodi').value = d.prodi;
            document.getElementById('edit_user_active').value = d.active;
            document.getElementById('modalEditUser').classList.remove('hidden');
        }
    </script>
</body>
</html>