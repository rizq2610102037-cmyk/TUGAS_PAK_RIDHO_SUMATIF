<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun Pengguna - Admin PIJAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin &rsaquo; Otorisasi & Keamanan</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Manajemen Akun Pengguna</h1>
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

                    <a href="{{ route('pijar.jadwal-eksternal') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Jadwal Pihak Eksternal
                    </a>

                    {{-- Menu Aktif: Manajemen Akun --}}
                    <a href="{{ route('pijar.manajemen-akun') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
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

                {{-- HEADER HALAMAN + TOMBOL EKSPOR & TAMBAH AKUN BARU --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-extrabold tracking-wider uppercase text-[#0A4D29]">OTORISASI & KEAMANAN / AKSES INSTITUSIONAL</p>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mt-0.5">Manajemen Akun Pengguna</h2>
                        <p class="text-xs text-gray-600 mt-1">Kelola otorisasi akun pengguna sistem: Kaprodi, Unit SUAR, dan Biro KERMA.</p>
                    </div>
                    <div class="flex items-center gap-2.5 shrink-0">
                        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-white border border-gray-200 hover:bg-gray-50 text-gray-800 font-bold text-xs shadow-xs cursor-pointer">
                            ⬇ Ekspor Data
                        </button>
                        <button type="button" onclick="document.getElementById('modalTambahUser').classList.remove('hidden')" class="px-4 py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs cursor-pointer">
                            👤+ &nbsp;+ Tambah Akun Baru
                        </button>
                    </div>
                </div>

                {{-- 4 KARTU METRIK PENGGUNA (PERSIS LAYOUT FOTO) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    {{-- 1. Total Pengguna --}}
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

                    {{-- 2. Akun Kaprodi --}}
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

                    {{-- 3. Unit SUAR --}}
                    <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col justify-between">
                        <div class="flex justify-between items-start">
                            <p class="text-[11px] font-extrabold text-gray-400 uppercase">UNIT SUAR</p>
                            <span class="w-9 h-9 rounded-xl bg-[#F57C00] text-white flex items-center justify-center text-sm">🔬</span>
                        </div>
                        <div class="my-3 flex items-baseline gap-2">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $totalSuar }}</span>
                            <span class="text-xs text-gray-500 font-medium">Akademik & Riset</span>
                        </div>
                        <p class="text-[11px] text-gray-500 pt-2 border-t border-gray-100">
                            <strong class="text-[#F57C00]">Semua Unit</strong> berstatus verifikator
                        </p>
                    </div>

                    {{-- 4. Biro KERMA --}}
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
                            <strong class="text-[#0A4D29]">100%</strong> sinkron ke jadwal dosen luar
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
                        <option value="kaprodi" {{ $role === 'kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                        <option value="admin_suar" {{ $role === 'admin_suar' ? 'selected' : '' }}>SUAR</option>
                        <option value="admin_kerma" {{ $role === 'admin_kerma' ? 'selected' : '' }}>KERMA</option>
                        <option value="admin_pijar" {{ $role === 'admin_pijar' ? 'selected' : '' }}>Superadmin PIJAR</option>
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

                    <a href="{{ route('pijar.manajemen-akun') }}" title="Reset Filter" class="p-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-600 font-bold">
                        ↻
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
                                    <th class="py-3.5 px-4">DIBUAT PADA</th>
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
                                                    <p class="text-[11px] text-gray-500">NIDN/NIP: {{ $u->nip_nidn ?? '041208810' . $u->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 font-semibold text-gray-700">
                                            {{ $u->username ?? explode('@', $u->email)[0] }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <a href="mailto:{{ $u->email }}" class="text-[#0A4D29] hover:underline font-medium">{{ $u->email }}</a>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($u->role === 'kaprodi')
                                                <span class="inline-block bg-[#0A4D29] text-white font-bold px-3 py-1 rounded-full text-[11px]">• Kaprodi</span>
                                            @elseif ($u->role === 'admin_suar')
                                                <span class="inline-block bg-[#F57C00] text-white font-bold px-3 py-1 rounded-full text-[11px]">• SUAR</span>
                                            @elseif ($u->role === 'admin_kerma')
                                                <span class="inline-block bg-[#117A65] text-white font-bold px-3 py-1 rounded-full text-[11px]">• KERMA</span>
                                            @else
                                                <span class="inline-block bg-gray-800 text-white font-bold px-3 py-1 rounded-full text-[11px]">• PIJAR</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 font-medium text-gray-800">
                                            @if ($u->prodi)
                                                S1 {{ $u->prodi }}
                                            @elseif ($u->role === 'admin_suar')
                                                Unit Riset & Pengajaran Terpadu
                                            @elseif ($u->role === 'admin_kerma')
                                                Biro Kerjasama & Kemitraan Industri
                                            @else
                                                Pusat Informasi Jadwal & Akademik
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($u->is_active)
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-0.5 rounded-full text-[11px]">• Aktif</span>
                                            @else
                                                <span class="inline-block bg-gray-200 text-gray-600 font-bold px-3 py-0.5 rounded-full text-[11px]">• Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-gray-500">
                                            {{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '24 Mar 2026' }}
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
                                        <td colspan="9" class="py-10 text-center text-gray-500">Tidak ada akun pengguna ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100">
                        {{ $users->links() }}
                    </div>
                </div>

                {{-- BANNER BAWAH: KEBIJAKAN PRIVILESE & KONTROL AKSES KAMPUS --}}
                <div class="bg-white rounded-2xl p-5 shadow-xs border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-[#0A4D29] text-white flex items-center justify-center text-xl shrink-0">
                            🛡️
                        </div>
                        <div>
                            <h4 class="font-extrabold text-base text-gray-900">Kebijakan Privilese & Kontrol Akses Kampus</h4>
                            <p class="text-xs text-gray-500 mt-0.5 max-w-2xl">Superadmin memiliki otoritas penuh untuk menangguhkan, mengatur ulang sesi multi-faktor, dan merekam audit jejak login setiap akun unit demi integritas alokasi jadwal.</p>
                        </div>
                    </div>

                    <button type="button" onclick="alert('Audit Log Aktif: Semua aktivitas login Kaprodi, SUAR, dan KERMA tercatat otomatis pada sistem.')" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs shrink-0 cursor-pointer">
                        Log Audit Akses
                    </button>
                </div>

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
                            <option value="kaprodi">Kaprodi</option>
                            <option value="admin_suar">Admin SUAR</option>
                            <option value="admin_kerma">Biro KERMA</option>
                            <option value="admin_pijar">Superadmin PIJAR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Program Studi (Jika Kaprodi)</label>
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
                    <input type="password" name="password" value="password123" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
                            <option value="kaprodi">Kaprodi</option>
                            <option value="admin_suar">Admin SUAR</option>
                            <option value="admin_kerma">Biro KERMA</option>
                            <option value="admin_pijar">Superadmin PIJAR</option>
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