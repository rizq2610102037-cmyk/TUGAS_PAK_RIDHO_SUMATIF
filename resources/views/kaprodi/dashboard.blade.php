<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Kaprodi - Pengajuan Jadwal & Praktisi Mengajar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex flex-col">

    <header class="w-full flex flex-col md:flex-row shadow-sm sticky top-0 z-30">
        <div class="w-full md:w-3/4 bg-[#0A4D29] px-6 py-4 flex items-center justify-between">
            <div>
                <p class="text-[11px] text-[#B7F4D1] font-semibold">Portal Kaprodi &rsaquo; Program Studi {{ $user->prodi ?? 'Universitas Pradita' }}</p>
                <h1 class="text-white text-xl font-extrabold">Pengajuan Jadwal Kuliah &amp; Praktisi Mengajar (PIJAR &amp; SUAR)</h1>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs cursor-pointer">
                    Logout
                </button>
            </form>
        </div>
        <div class="w-full md:w-1/4 bg-[#F57C00] px-6 py-3 flex flex-col justify-center items-end text-right text-white">
            <span class="font-bold text-base leading-tight">{{ $user->name }}</span>
            <span class="text-xs text-white/90">Kaprodi {{ $user->prodi ?? 'Prodi' }}</span>
        </div>
    </header>

    <main class="flex-1 p-4 md:p-6 max-w-6xl w-full mx-auto space-y-6">

        @if (session('success'))
            <div class="bg-[#D5F5E3] border border-[#0A4D29] text-[#0A4D29] px-4 py-3 rounded-xl text-xs font-bold">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-[#FADBD8] border border-[#C0392B] text-[#C0392B] px-4 py-3 rounded-xl text-xs font-bold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM PENGAJUAN JADWAL & PRAKTISI (TERFILTER OTOMATIS SESUAI JURUSAN KAPRODI) --}}
        <div class="bg-white rounded-2xl p-6 shadow-xs border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b pb-3 mb-4">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#F57C00]">FORMULIR PENGAJUAN BARU</span>
                    <h2 class="text-lg font-extrabold text-[#0A4D29]">Ajukan Jadwal Kuliah / Praktisi Industri</h2>
                    <p class="text-xs text-gray-500">Daftar mata kuliah otomatis difilter khusus untuk Program Studi <strong>{{ $user->prodi ?? 'Anda' }}</strong>. Wajib sertakan <strong>email kampus dosen (@pradita.ac.id)</strong> untuk aktivasi login.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 bg-[#E8F5E9] text-[#0A4D29] font-extrabold text-xs px-3.5 py-1.5 rounded-full shrink-0">
                    🎓 Prodi: {{ $user->prodi ?? 'Belum Diatur' }} ({{ $courses->count() }} MK)
                </span>
            </div>

            <form action="{{ route('kaprodi.pengajuan.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                @csrf

                {{-- 1. Mata Kuliah (Otomatis Khusus Jurusan Kaprodi Login) --}}
                <div class="md:col-span-2">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-bold">Mata Kuliah Program Studi {{ $user->prodi ?? '' }} <span class="text-red-600">*</span></label>
                        <span class="text-[10px] font-bold text-[#0A4D29]">{{ $courses->count() }} MK tersedia</span>
                    </div>
                    <select name="course_id" required class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50 font-semibold text-gray-800">
                        <option value="">-- Pilih Mata Kuliah {{ $user->prodi ?? '' }} --</option>
                        @forelse($courses as $c)
                            <option value="{{ $c->id }}" {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->kode_mk }} - {{ $c->nama_mk }} ({{ $c->sks }} SKS • Sem. {{ $c->semester ?? '-' }})
                            </option>
                        @empty
                            <option value="" disabled>Belum ada mata kuliah untuk prodi {{ $user->prodi }}</option>
                        @endforelse
                    </select>
                </div>

                {{-- 2. Tahun Ajaran & Semester --}}
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-bold mb-1">Tahun Ajaran</label>
                        <select name="tahun_ajaran" required class="w-full border border-gray-300 rounded-xl px-2 py-2 bg-gray-50">
                            @foreach(['2026-2027', '2027-2028', '2028-2029', '2029-2030', '2030-2031'] as $th)
                                <option value="{{ $th }}" {{ old('tahun_ajaran') === $th ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Semester</label>
                        <select name="semester_jenis" required class="w-full border border-gray-300 rounded-xl px-2 py-2 bg-gray-50">
                            <option value="Ganjil" {{ old('semester_jenis') === 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester_jenis') === 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold mb-1">Ke-</label>
                        <select name="semester_angka" required class="w-full border border-gray-300 rounded-xl px-2 py-2 bg-gray-50">
                            @for($s = 1; $s <= 8; $s++)
                                <option value="{{ $s }}" {{ old('semester_angka') == $s ? 'selected' : '' }}>Sem. {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- 3. Ruang Kelas + Tombol Cepat + Tambah Ruang Baru --}}
                <div class="md:col-span-3">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block font-bold">Ruang Kelas <span class="text-red-600">*</span></label>
                        <button type="button" onclick="toggleKaprodiQuickRoom()" class="text-[11px] font-extrabold text-[#F57C00] hover:underline cursor-pointer">
                            + Tambah Ruang Baru
                        </button>
                    </div>

                    <select name="room_id" id="kaprodi_select_room" required class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                        @foreach($rooms as $r)
                            <option value="{{ $r->id }}" {{ old('room_id') == $r->id ? 'selected' : '' }}>
                                {{ $r->kode_ruang }} - {{ $r->nama_ruang }} (Kapasitas {{ $r->kapasitas }})
                            </option>
                        @endforeach
                    </select>

                    {{-- Panel Input Tambah Ruang Cepat (Inline) --}}
                    <div id="kaprodiQuickRoomBox" class="hidden mt-2.5 p-3.5 rounded-xl bg-[#FAFCEB] border border-[#0A4D29]/25 space-y-2.5">
                        <div class="flex justify-between items-center">
                            <span class="text-[11px] font-extrabold text-[#0A4D29]">Tambah Data Ruang Kelas Baru</span>
                            <button type="button" onclick="toggleKaprodiQuickRoom()" class="text-xs text-gray-400 hover:text-gray-700 font-bold cursor-pointer">✕</button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <input type="text" id="kp_new_kode_ruang" placeholder="Kode (cth: R-402)" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                            <input type="text" id="kp_new_nama_ruang" placeholder="Nama (cth: Lab Komputer 2)" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                            <input type="number" id="kp_new_kapasitas_ruang" value="40" min="1" placeholder="Kapasitas" class="border border-gray-300 rounded-lg px-2.5 py-1.5 bg-white text-xs">
                        </div>

                        <p id="kpQuickRoomError" class="hidden text-[11px] text-red-600 font-bold"></p>

                        <div class="flex justify-end">
                            <button type="button" onclick="submitKaprodiQuickRoom()" id="btnKpSaveQuickRoom" class="px-3.5 py-1.5 rounded-lg bg-[#0A4D29] hover:bg-[#083b1f] text-white font-bold text-[11px] cursor-pointer">
                                ✓ Simpan &amp; Pilih Ruang Ini
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 4. Dosen Pengampu & Email Kampus (WAJIB @pradita.ac.id) --}}
                <div>
                    <label class="block font-bold mb-1">Nama Dosen Pengampu <span class="text-red-600">*</span></label>
                    <input type="text" list="list_dosen_kaprodi" name="dosen_pengajar" value="{{ old('dosen_pengajar') }}" required placeholder="Pilih atau ketik nama lengkap dosen..." class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-gray-50">
                    <datalist id="list_dosen_kaprodi">
                        @foreach($dosens as $d)
                            <option value="{{ $d->name }}">
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block font-bold mb-1">Email Kampus Dosen <span class="text-red-600">*</span></label>
                    <input 
                        type="email" 
                        name="dosen_email" 
                        value="{{ old('dosen_email') }}"
                        required 
                        pattern="^[a-zA-Z0-9._%+\-]+@pradita\.ac\.id$"
                        title="Wajib menggunakan email kampus resmi (@pradita.ac.id)"
                        placeholder="nama.dosen@pradita.ac.id" 
                        class="w-full border border-gray-300 rounded-xl px-3 py-2 bg-white"
                    >
                    <p class="text-[10px] text-gray-500 mt-1">Wajib email kampus <strong>@pradita.ac.id</strong> untuk login akun Dosen.</p>
                </div>

                <div>
                    <label class="block font-bold mb-1">Dokumen RPS / Silabus Awal (PDF/PPT/DOCX)</label>
                    <input type="file" name="dokumen_bahan_ajar" accept=".pdf,.doc,.docx,.ppt,.pptx" class="w-full border border-gray-300 rounded-xl px-2.5 py-1.5 bg-gray-50">
                </div>

                {{-- 5. Identitas Praktisi Industri (SUAR) --}}
                <div>
                    <label class="block font-bold mb-1">Nama Praktisi Industri (Opsional jika non-SUAR)</label>
                    <input type="text" name="praktisi_nama" value="{{ old('praktisi_nama') }}" placeholder="Contoh: Ir. Andi Pratama, M.T." class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold mb-1">Email Praktisi (Untuk Akun Portal Otomatis)</label>
                    <input type="email" name="praktisi_email" value="{{ old('praktisi_email') }}" placeholder="andi.pratama@perusahaan.com" class="w-full border border-gray-300 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block font-bold mb-1">Instansi Mitra &amp; Jabatan</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="mitra_instansi" value="{{ old('mitra_instansi') }}" placeholder="PT Telkom" class="w-full border border-gray-300 rounded-xl px-2.5 py-2">
                        <input type="text" name="jabatan_praktisi" value="{{ old('jabatan_praktisi') }}" placeholder="VP Tech" class="w-full border border-gray-300 rounded-xl px-2.5 py-2">
                    </div>
                </div>

                <div class="md:col-span-3 flex flex-wrap items-center justify-between gap-3 pt-2 border-t">
                    <label class="inline-flex items-center gap-2 font-semibold text-gray-700 cursor-pointer">
                        <input type="checkbox" name="is_external" value="1" {{ old('is_external', '1') ? 'checked' : '' }} class="rounded text-[#0A4D29]">
                        <span>Tandai sebagai Program SUAR (Praktisi Industri)</span>
                    </label>

                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-extrabold cursor-pointer">
                        + Kirim Pengajuan ke Admin Pusat
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL RIWAYAT PENGAJUAN & INFO AKUN OTOMATIS SETELAH ACC --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="p-5 border-b">
                <h3 class="font-extrabold text-base text-gray-900">Status Pengajuan &amp; Kredensial Akun Otomatis ({{ $user->prodi ?? 'Prodi' }})</h3>
                <p class="text-xs text-gray-500">Setelah pengajuan di-ACC oleh Admin Pusat, informasi akun login Email Kampus Dosen &amp; Praktisi akan tampil di sini.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-[10px] font-extrabold uppercase border-b">
                            <th class="py-3 px-4">MATA KULIAH</th>
                            <th class="py-3 px-4">DOSEN &amp; EMAIL KAMPUS</th>
                            <th class="py-3 px-4">PERIODE &amp; RUANG</th>
                            <th class="py-3 px-4 text-center">STATUS VERIFIKASI</th>
                            <th class="py-3 px-4">KREDENSIAL AKUN OTOMATIS (SETELAH ACC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($schedules as $s)
                            <tr>
                                <td class="py-3.5 px-4">
                                    <p class="font-bold text-gray-900">{{ $s->course->nama_mk }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $s->course->kode_mk }} • {{ $s->course->prodi }}</p>
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-semibold text-gray-900">{{ $s->dosen_pengajar }}</p>
                                    @if($s->dosen_email_kampus || $s->dosen_email)
                                        <p class="text-[11px] text-[#0A4D29] font-semibold">✉️ {{ $s->dosen_email_kampus ?? $s->dosen_email }}</p>
                                    @endif
                                    @if($s->praktisi_nama)
                                        <p class="text-[11px] text-[#F57C00] font-bold mt-0.5">Praktisi: {{ $s->praktisi_nama }} ({{ $s->mitra_instansi ?? '-' }})</p>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-semibold">TA {{ $s->tahun_ajaran ?? '2026-2027' }} ({{ $s->semester_jenis ?? 'Ganjil' }})</p>
                                    <p class="text-[11px] text-gray-500">Ruang: {{ $s->room->kode_ruang ?? '-' }}</p>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if ($s->status === 'approved')
                                        <span class="bg-[#D5F5E3] text-[#0A4D29] font-bold px-3 py-1 rounded-full text-[11px]">✓ ACC / Disetujui</span>
                                    @elseif ($s->status === 'cancelled' || $s->status === 'rejected')
                                        <span class="bg-[#FADBD8] text-[#C0392B] font-bold px-3 py-1 rounded-full text-[11px]">✕ Ditolak</span>
                                        @if($s->alasan_penolakan)
                                            <p class="text-[10px] text-red-600 mt-1">{{ $s->alasan_penolakan }}</p>
                                        @endif
                                    @else
                                        <span class="bg-[#FFF0E0] text-[#F57C00] font-bold px-3 py-1 rounded-full text-[11px]">⏳ Menunggu ACC</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($s->status === 'approved' && $s->akun_generated_info)
                                        <div class="bg-[#FAFCEB] border border-[#0A4D29]/20 rounded-lg p-2 text-[11px] text-[#0A4D29] font-semibold">
                                            {{ $s->akun_generated_info }}
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Terbit otomatis saat disetujui</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">Belum ada pengajuan jadwal atau praktisi untuk prodi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function toggleKaprodiQuickRoom() {
            const box = document.getElementById('kaprodiQuickRoomBox');
            document.getElementById('kpQuickRoomError').classList.add('hidden');
            box.classList.toggle('hidden');
        }

        async function submitKaprodiQuickRoom() {
            const kode = document.getElementById('kp_new_kode_ruang').value.trim();
            const nama = document.getElementById('kp_new_nama_ruang').value.trim();
            const kapasitas = document.getElementById('kp_new_kapasitas_ruang').value;
            const errEl = document.getElementById('kpQuickRoomError');
            const btn = document.getElementById('btnKpSaveQuickRoom');

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
                    const select = document.getElementById('kaprodi_select_room');
                    const option = document.createElement('option');
                    option.value = data.room.id;
                    option.textContent = `${data.room.kode_ruang} - ${data.room.nama_ruang} (Kapasitas ${data.room.kapasitas})`;
                    option.selected = true;
                    select.appendChild(option);

                    document.getElementById('kp_new_kode_ruang').value = '';
                    document.getElementById('kp_new_nama_ruang').value = '';
                    document.getElementById('kp_new_kapasitas_ruang').value = '40';
                    document.getElementById('kaprodiQuickRoomBox').classList.add('hidden');
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