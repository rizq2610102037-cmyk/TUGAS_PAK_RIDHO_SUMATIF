<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data & Kompetensi Praktisi - Admin SUAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin SUAR &rsaquo; Direktori Praktisi</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Manajemen Data & Kompetensi Praktisi</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('suar.praktisi') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama praktisi, mitra, jabatan, atau keahlian..." class="w-full pl-9 pr-4 py-1.5 rounded-full bg-[#E8F5E9] text-xs text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F57C00]">
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

        @if(session('success'))
            <div class="bg-[#D5F5E3] border border-[#0A4D29] text-[#0A4D29] px-4 py-3 rounded-xl flex justify-between items-center text-sm font-medium shadow-xs">
                <span>✅ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="font-bold text-lg leading-none cursor-pointer">&times;</button>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- SIDEBAR MENU ADMIN SUAR --}}
            <aside class="w-full lg:w-64 bg-[#0A4D29] rounded-2xl p-4 text-white flex flex-col justify-between lg:min-h-[760px] shrink-0 shadow-md">
                <div class="space-y-4">
                    <div class="px-3 py-2 bg-white/10 rounded-xl">
                        <p class="text-[10px] uppercase tracking-wider text-[#B7F4D1] font-bold">UNIT AKADEMIK & INDUSTRI</p>
                        <p class="text-sm font-extrabold text-white">Admin SUAR</p>
                    </div>

                    <nav class="space-y-2">
                        <a href="{{ route('suar.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Pengajuan & Penugasan
                        </a>

                        <a href="{{ route('suar.praktisi') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Data & Kompetensi Praktisi
                        </a>
                    </nav>
                </div>

                <div class="pt-8 mt-8 border-t border-white/15 space-y-2">
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

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#0A4D29]">● DIREKTORI KEPAKARAN INDUSTRI</span>
                        <h2 class="text-2xl font-extrabold text-gray-900">Data & Kompetensi Praktisi</h2>
                        <p class="text-xs text-gray-600 mt-0.5">Kelola profil praktisi non-NIDN Pradita, bidang keahlian, jabatan industri, serta pengalaman profesional.</p>
                    </div>

                    <button type="button" onclick="document.getElementById('modalTambahPraktisi').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs cursor-pointer shrink-0">
                        + Tambah Praktisi Baru
                    </button>
                </div>

                {{-- 4 KARTU METRIK PRAKTISI --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">TOTAL PRAKTISI</p>
                        <p class="text-3xl font-extrabold text-gray-900 my-1.5">{{ $totalPraktisi }}</p>
                        <p class="text-[11px] text-[#0A4D29] font-semibold">{{ $praktisiAktif }} Aktif Mengajar</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">PERUSAHAAN MITRA</p>
                        <p class="text-3xl font-extrabold text-gray-900 my-1.5">{{ $totalMitra }}</p>
                        <p class="text-[11px] text-gray-500">Instansi industri asal</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">BIDANG KEAHLIAN</p>
                        <p class="text-3xl font-extrabold text-[#F57C00] my-1.5">{{ $totalKeahlian }}</p>
                        <p class="text-[11px] text-gray-500">Klaster kompetensi</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">VALIDASI SOP PIJAR</p>
                        <p class="text-xl font-extrabold text-[#0A4D29] my-2">Non-NIDN</p>
                        <p class="text-[11px] text-gray-500">Memenuhi Aturan 6.1 Poin 2</p>
                    </div>
                </div>

                {{-- TABEL DIREKTORI PRAKTISI --}}
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-[11px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4">NAMA PRAKTISI & KONTAK</th>
                                    <th class="py-3.5 px-4">MITRA INSTANSI & JABATAN</th>
                                    <th class="py-3.5 px-4">KOMPETENSI & KEAHLIAN</th>
                                    <th class="py-3.5 px-4">PENGALAMAN INDUSTRI</th>
                                    <th class="py-3.5 px-4 text-center">STATUS</th>
                                    <th class="py-3.5 px-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($practitioners as $p)
                                    <tr class="hover:bg-[#FAFCEB] transition">
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-900">{{ $p->nama }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $p->kontak ?? 'Kontak belum diisi' }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-[#0A4D29]">{{ $p->mitra_instansi }}</p>
                                            <p class="text-[11px] text-gray-600">{{ $p->jabatan }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="bg-[#FFF0E0] text-[#F57C00] font-bold px-2.5 py-1 rounded-full text-[11px]">
                                                {{ $p->bidang_keahlian }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 max-w-xs text-gray-600">
                                            {{ \Illuminate\Support\Str::limit($p->pengalaman ?? 'Praktisi profesional industri mitra.', 85) }}
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($p->is_active)
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-2.5 py-1 rounded-full text-[11px]">● Aktif</span>
                                            @else
                                                <span class="inline-block bg-gray-200 text-gray-600 font-bold px-2.5 py-1 rounded-full text-[11px]">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button 
                                                type="button"
                                                onclick="openEditPraktisi(this)"
                                                data-id="{{ $p->id }}"
                                                data-nama="{{ $p->nama }}"
                                                data-mitra="{{ $p->mitra_instansi }}"
                                                data-jabatan="{{ $p->jabatan }}"
                                                data-keahlian="{{ $p->bidang_keahlian }}"
                                                data-pengalaman="{{ $p->pengalaman }}"
                                                data-kontak="{{ $p->kontak }}"
                                                data-active="{{ $p->is_active ? '1' : '0' }}"
                                                class="px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-100 font-bold text-gray-700 text-[11px] cursor-pointer"
                                            >
                                                Edit Profil & Keahlian
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            Belum ada data praktisi terdaftar. Klik <strong>+ Tambah Praktisi Baru</strong> untuk mulai mengisi direktori praktisi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100">
                        {{ $practitioners->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH PRAKTISI BARU --}}
    <div id="modalTambahPraktisi" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Tambah Data Praktisi Baru</h3>
                <button type="button" onclick="document.getElementById('modalTambahPraktisi').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>
            <form action="{{ route('suar.praktisi.store') }}" method="POST" class="space-y-3 text-xs">
                @csrf
                <div>
                    <label class="block font-bold mb-1">Nama Lengkap & Gelar Praktisi</label>
                    <input type="text" name="nama" required placeholder="Contoh: Hendra Wijaya, S.Kom., M.M." class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Perusahaan / Instansi Mitra</label>
                        <input type="text" name="mitra_instansi" required placeholder="Contoh: PT Telkom Indonesia" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Jabatan di Industri</label>
                        <input type="text" name="jabatan" required placeholder="Contoh: VP Product Management" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Kompetensi / Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" required placeholder="Contoh: Cloud Architecture & DevOps" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Kontak (Email / WhatsApp)</label>
                        <input type="text" name="kontak" placeholder="0812xxxx / email@perusahaan.com" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Ringkasan Pengalaman & Sertifikasi</label>
                    <textarea name="pengalaman" rows="3" placeholder="Jelaskan pengalaman kerja industri, proyek, atau sertifikasi keahlian..." class="w-full border border-gray-300 rounded-xl p-3"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalTambahPraktisi').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-200 font-bold cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Praktisi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT PRAKTISI & KOMPETENSI --}}
    <div id="modalEditPraktisi" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-extrabold text-[#0A4D29]">Perbarui Profil & Kompetensi Praktisi</h3>
                <button type="button" onclick="document.getElementById('modalEditPraktisi').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
            </div>
            <form id="formEditPraktisi" method="POST" class="space-y-3 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold mb-1">Nama Lengkap & Gelar Praktisi</label>
                    <input type="text" name="nama" id="edit_nama" required class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Perusahaan / Instansi Mitra</label>
                        <input type="text" name="mitra_instansi" id="edit_mitra" required class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Jabatan di Industri</label>
                        <input type="text" name="jabatan" id="edit_jabatan" required class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold mb-1">Kompetensi / Bidang Keahlian</label>
                        <input type="text" name="bidang_keahlian" id="edit_keahlian" required class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Kontak</label>
                        <input type="text" name="kontak" id="edit_kontak" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block font-bold mb-1">Ringkasan Pengalaman & Sertifikasi</label>
                    <textarea name="pengalaman" id="edit_pengalaman" rows="3" class="w-full border border-gray-300 rounded-xl p-3"></textarea>
                </div>
                <div>
                    <label class="block font-bold mb-1">Status Ketersediaan</label>
                    <select name="is_active" id="edit_active" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                        <option value="1">● Aktif (Tersedia untuk Penugasan)</option>
                        <option value="0">Nonaktif</option>
                    </select>

                    <div class="flex justify-between items-center gap-2 pt-3 border-t">
                        <button type="button" onclick="confirmDeletePraktisi()" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold cursor-pointer">
                            🗑️ Hapus Praktisi
                        </button>
                        <div class="flex gap-2">
                            <button type="button" onclick="document.getElementById('modalEditPraktisi').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-200 font-bold cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A4D29] text-white font-bold cursor-pointer">Simpan Perubahan</button>
                        </div>
                    </div>
                </div>
            </form>

            <form id="formDeletePraktisi" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
       function openEditPraktisi(btn) {
                    const d = btn.dataset;
                    document.getElementById('formEditPraktisi').action = '/suar/praktisi/' + d.id;
                    document.getElementById('formDeletePraktisi').action = '/suar/praktisi/' + d.id;
                    document.getElementById('edit_nama').value = d.nama;
                    document.getElementById('edit_mitra').value = d.mitra;
                    document.getElementById('edit_jabatan').value = d.jabatan;
                    document.getElementById('edit_keahlian').value = d.keahlian;
                    document.getElementById('edit_kontak').value = d.kontak || '';
                    document.getElementById('edit_pengalaman').value = d.pengalaman || '';
                    document.getElementById('edit_active').value = d.active;
                    document.getElementById('modalEditPraktisi').classList.remove('hidden');
                }

                function confirmDeletePraktisi() {
                    const nama = document.getElementById('edit_nama').value;
                    if (confirm('Yakin ingin menghapus data praktisi "' + nama + '" secara permanen?')) {
                        document.getElementById('formDeletePraktisi').submit();
                    }
                }

                function confirmDeletePraktisi() {
                    const nama = document.getElementById('edit_nama').value;
                    if (confirm('Yakin ingin menghapus data praktisi "' + nama + '" secara permanen?')) {
                        document.getElementById('formDeletePraktisi').submit();
                    }
                }
    </script>
</body>
</html>