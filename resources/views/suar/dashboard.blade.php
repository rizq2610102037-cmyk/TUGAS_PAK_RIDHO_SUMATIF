<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penugasan Praktisi SUAR - Universitas Pradita</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    {{-- TOP NAVBAR --}}
    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Admin SUAR &rsaquo; Penugasan Praktisi</p>
                <h1 class="text-white text-xl font-extrabold tracking-tight">Penugasan & Status Praktisi Mengajar</h1>
            </div>

            <div class="flex items-center gap-3 w-full max-w-md justify-end">
                <form action="{{ route('suar.dashboard') }}" method="GET" class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari mata kuliah, prodi, atau praktisi..." class="w-full pl-9 pr-4 py-1.5 rounded-full bg-[#E8F5E9] text-xs text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#F57C00]">
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
                        <a href="{{ route('suar.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#B7F4D1] text-[#0A4D29] font-bold text-sm shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Pengajuan & Penugasan
                        </a>

                        <a href="{{ route('suar.praktisi') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-[#B7F4D1] hover:bg-white/10 transition text-sm font-medium">
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
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#0A4D29]">● MODUL PENUGASAN PRAKTISI SUAR</span>
                        <h2 class="text-2xl font-extrabold text-gray-900">Pengajuan Disetujui & Penugasan Praktisi</h2>
                        <p class="text-xs text-gray-600 mt-0.5">Daftar pengajuan mata kuliah yang telah disetujui Admin PIJAR untuk ditugaskan praktisi industri dan diperbarui status pelaksanaannya.</p>
                    </div>
                    <a href="{{ route('suar.praktisi') }}" class="px-4 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-xs shadow-xs shrink-0">
                        + Kelola Data Praktisi ({{ $totalPraktisiAktif }} Aktif)
                    </a>
                </div>

                {{-- 4 KARTU METRIK UTAMA --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">PENGAJUAN DISETUJUI</p>
                        <p class="text-3xl font-extrabold text-gray-900 my-1.5">{{ $totalDisetujui }}</p>
                        <p class="text-[11px] text-[#0A4D29] font-semibold">Lolos verifikasi PIJAR</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">BELUM DITUGASKAN</p>
                        <p class="text-3xl font-extrabold text-[#F57C00] my-1.5">{{ $belumDitugaskan }}</p>
                        <p class="text-[11px] text-gray-500">Menunggu penetapan praktisi</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">SIAP / SEDANG MENGAJAR</p>
                        <p class="text-3xl font-extrabold text-[#0A4D29] my-1.5">{{ $siapMengajar }}</p>
                        <p class="text-[11px] text-gray-500">Praktisi sudah ditetapkan</p>
                    </div>

                    <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100">
                        <p class="text-[11px] font-bold text-gray-500 uppercase">PENUGASAN SELESAI</p>
                        <p class="text-3xl font-extrabold text-gray-900 my-1.5">{{ $selesaiMengajar }}</p>
                        <p class="text-[11px] text-[#0A4D29] font-semibold">Terlaksana di kelas</p>
                    </div>
                </div>

                {{-- FILTER STATUS PENUGASAN --}}
                <div class="bg-white rounded-2xl p-4 shadow-xs border border-gray-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('suar.dashboard') }}" class="px-3.5 py-1.5 rounded-full font-bold {{ !$status ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Semua ({{ $totalDisetujui }})</a>
                        <a href="{{ route('suar.dashboard', ['status_eksternal' => 'pending']) }}" class="px-3.5 py-1.5 rounded-full font-bold {{ $status === 'pending' ? 'bg-[#F57C00] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Menunggu Praktisi ({{ $belumDitugaskan }})</a>
                        <a href="{{ route('suar.dashboard', ['status_eksternal' => 'ready_to_teach']) }}" class="px-3.5 py-1.5 rounded-full font-bold {{ $status === 'ready_to_teach' ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Ready to Teach</a>
                        <a href="{{ route('suar.dashboard', ['status_eksternal' => 'ongoing']) }}" class="px-3.5 py-1.5 rounded-full font-bold {{ $status === 'ongoing' ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Ongoing</a>
                        <a href="{{ route('suar.dashboard', ['status_eksternal' => 'completed']) }}" class="px-3.5 py-1.5 rounded-full font-bold {{ $status === 'completed' ? 'bg-[#0A4D29] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Completed</a>
                    </div>
                </div>

                {{-- TABEL PENGAJUAN DISETUJUI & PENUGASAN PRAKTISI --}}
                <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-[11px] font-extrabold uppercase tracking-wider border-b border-gray-200">
                                    <th class="py-3.5 px-4">MATA KULIAH & PRODI</th>
                                    <th class="py-3.5 px-4">DOSEN PENGAMPU</th>
                                    <th class="py-3.5 px-4">JADWAL & RUANG</th>
                                    <th class="py-3.5 px-4">PRAKTISI DITUGASKAN</th>
                                    <th class="py-3.5 px-4 text-center">STATUS PENUGASAN</th>
                                    <th class="py-3.5 px-4 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @forelse ($schedules as $item)
                                    <tr class="hover:bg-[#FAFCEB] transition">
                                        <td class="py-4 px-4">
                                            <span class="bg-[#E8F5E9] text-[#0A4D29] font-bold px-2 py-0.5 rounded text-[10px]">{{ $item->course->kode_mk }}</span>
                                            <p class="font-bold text-gray-900 mt-1">{{ $item->course->nama_mk }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $item->course->prodi }} • Pertemuan ke-{{ $item->pertemuan_ke ?? 1 }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-bold text-gray-800">{{ $item->dosen_pengajar }}</p>
                                            @if($item->dokumen_bahan_ajar)
                                                <a href="{{ asset('storage/' . $item->dokumen_bahan_ajar) }}" target="_blank" class="text-[11px] text-[#F57C00] font-bold hover:underline inline-block mt-1">
                                                    📄 Lihat Berkas RPS
                                                </a>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <p class="font-semibold text-gray-800">{{ $item->hari_sesi }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $item->waktu_sesi }} • Ruang {{ $item->room->kode_ruang }}</p>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($item->praktisi_nama)
                                                <p class="font-extrabold text-[#0A4D29]">{{ $item->praktisi_nama }}</p>
                                                <p class="text-[11px] text-gray-500">{{ $item->mitra_instansi ?? '-' }} ({{ $item->jabatan_praktisi ?? 'Praktisi' }})</p>
                                            @else
                                                <span class="text-[#F57C00] font-bold italic">Belum ditetapkan</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($item->status_eksternal === 'completed')
                                                <span class="inline-block bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">✓ Selesai</span>
                                            @elseif($item->status_eksternal === 'ongoing')
                                                <span class="inline-block bg-blue-100 text-blue-700 font-bold px-3 py-1 rounded-full text-[11px]">● Berlangsung</span>
                                            @elseif($item->status_eksternal === 'ready_to_teach')
                                                <span class="inline-block bg-[#E8F5E9] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">✓ Siap Mengajar</span>
                                            @else
                                                <span class="inline-block bg-[#FFF0E0] text-[#F57C00] font-bold px-3 py-1 rounded-full text-[11px]">⏳ Menunggu Penugasan</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <button 
                                                type="button"
                                                onclick="openAssignModal(this)"
                                                data-id="{{ $item->id }}"
                                                data-mk="{{ $item->course->kode_mk }} - {{ $item->course->nama_mk }}"
                                                data-prodi="{{ $item->course->prodi }}"
                                                data-dosen="{{ $item->dosen_pengajar }}"
                                                data-status="{{ $item->status_eksternal ?? 'pending' }}"
                                                data-iks="{{ $item->nomor_iks ?? '' }}"
                                                data-catatan="{{ $item->catatan_admin ?? '' }}"
                                                class="px-3.5 py-1.5 rounded-lg bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-[11px] cursor-pointer shadow-xs"
                                            >
                                                Tugaskan / Update
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-10 text-center text-gray-500">
                                            Belum ada pengajuan yang disetujui oleh Admin PIJAR.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100">
                        {{ $schedules->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL TUGASKAN PRAKTISI & UPDATE STATUS PENUGASAN --}}
    <div id="modalAssign" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-start border-b pb-3 mb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F57C00]">PENUGASAN PRAKTISI SUAR</span>
                    <h3 id="assign_mk" class="text-base font-extrabold text-gray-900 mt-0.5">Mata Kuliah</h3>
                    <p id="assign_sub" class="text-xs text-gray-500">Prodi & Dosen Pengampu</p>
                </div>
                <button type="button" onclick="document.getElementById('modalAssign').classList.add('hidden')" class="text-gray-400 hover:text-gray-700 font-bold text-lg cursor-pointer">✕</button>
            </div>

            <form id="formAssign" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Pilih Praktisi Industri (Sesuai Kompetensi)</label>
                    <select name="practitioner_id" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-gray-50">
                        <option value="">-- Pilih Praktisi Terdaftar --</option>
                        @foreach($practitioners as $p)
                            <option value="{{ $p->id }}">{{ $p->nama }} — {{ $p->mitra_instansi }} ({{ $p->bidang_keahlian }})</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Praktisi belum ada di daftar? <a href="{{ route('suar.praktisi') }}" class="text-[#0A4D29] font-bold hover:underline">Tambah data praktisi baru &rarr;</a></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Status Penugasan Praktisi</label>
                        <select name="status_eksternal" id="assign_status" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-gray-50 font-semibold">
                            <option value="pending">⏳ Menunggu Konfirmasi Praktisi</option>
                            <option value="ready_to_teach">✓ Ready to Teach (Siap Mengajar)</option>
                            <option value="ongoing">● Ongoing (Sedang Berlangsung)</option>
                            <option value="completed">✓ Completed (Selesai Mengajar)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 mb-1">Nomor Dokumen IKS (Opsional)</label>
                        <input type="text" name="nomor_iks" id="assign_iks" placeholder="IKS/KERMA/2026/..." class="w-full border border-gray-300 rounded-xl px-3 py-2.5 bg-gray-50">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-1">Catatan Teknis Penugasan</label>
                    <textarea name="catatan_admin" id="assign_catatan" rows="2" placeholder="Catatan kesepakatan jadwal atau materi praktisi..." class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t">
                    <button type="button" onclick="document.getElementById('modalAssign').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 font-bold text-gray-700 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold cursor-pointer shadow-sm">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAssignModal(btn) {
            const d = btn.dataset;
            document.getElementById('formAssign').action = '/suar/schedules/' + d.id + '/assign';
            document.getElementById('assign_mk').textContent = d.mk;
            document.getElementById('assign_sub').textContent = d.prodi + ' • Dosen Pengampu: ' + d.dosen;
            document.getElementById('assign_status').value = d.status || 'pending';
            document.getElementById('assign_iks').value = d.iks || '';
            document.getElementById('assign_catatan').value = d.catatan || '';
            document.getElementById('modalAssign').classList.remove('hidden');
        }
    </script>
</body>
</html>