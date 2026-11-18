<aside class="w-full lg:w-64 bg-[#0A4D29] rounded-2xl p-4 text-white flex flex-col justify-between lg:min-h-[760px] shrink-0 shadow-md">
    <div class="space-y-4">
        <div class="px-3 py-2.5 bg-white/10 rounded-xl">
            <p class="text-[10px] uppercase tracking-wider text-[#B7F4D1] font-bold">ADMINISTRASI TERPADU</p>
            <p class="text-sm font-extrabold text-white">Admin PIJAR & SUAR</p>
        </div>

        <div class="space-y-1">
            <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-[#B7F4D1]/80 mb-1">Modul PIJAR</p>

            <a href="{{ route('pijar.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('pijar.dashboard') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Dashboard Utama
            </a>

            <a href="{{ route('pijar.manajemen-jadwal') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('pijar.manajemen-jadwal') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Manajemen Jadwal
            </a>

            <a href="{{ route('pijar.jadwal-eksternal') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('pijar.jadwal-eksternal') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Jadwal Eksternal
            </a>

            <a href="{{ route('pijar.manajemen-akun') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('pijar.manajemen-akun') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Manajemen Akun
            </a>

            <a href="{{ route('pijar.rekap-pengajaran') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('pijar.rekap-pengajaran') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Rekap Pengajaran
            </a>
        </div>

        <div class="pt-3 border-t border-white/15 space-y-1">
            <p class="px-3 text-[10px] font-extrabold uppercase tracking-wider text-[#F57C00] bg-white/10 py-1 rounded-md mb-1.5">Modul SUAR</p>

            <a href="{{ route('suar.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('suar.dashboard') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Penugasan Praktisi
            </a>

            <a href="{{ route('suar.praktisi') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm transition {{ request()->routeIs('suar.praktisi*') ? 'bg-[#B7F4D1] text-[#0A4D29] font-bold shadow-sm' : 'text-[#B7F4D1] hover:bg-white/10 font-medium' }}">
                Data & Kompetensi Praktisi
            </a>
        </div>
    </div>

    <div class="pt-6 mt-6 border-t border-white/15">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-[#B7F4D1] hover:bg-red-500/20 hover:text-white transition text-sm cursor-pointer text-left">
                Keluar
            </button>
        </form>
    </div>
</aside>