<form action="{{ route('login.post') }}" method="POST" class="space-y-4">
    @csrf

    {{-- Pesan Error Validasi / Gagal Login --}}
    @if ($errors->any())
        <div class="p-3 rounded-lg bg-red-500/20 border border-red-400 text-white text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <div>
        <input 
            type="email" 
            name="email" 
            value="{{ old('email') }}" 
            placeholder="Email address" 
            required
            class="w-full px-4 py-3 rounded-lg bg-transparent border border-[#F57C00] text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
        >
    </div>

    <div>
        <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            required
            class="w-full px-4 py-3 rounded-lg bg-transparent border border-[#F57C00] text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
        >
    </div>

    <div class="flex items-center space-x-2">
        <input 
            type="checkbox" 
            name="remember" 
            id="remember" 
            class="rounded border-white/50 text-[#F57C00] focus:ring-[#F57C00]"
        >
        <label for="remember" class="text-white text-sm">Keep me logged in</label>
    </div>

    <button 
        type="submit" 
        class="w-full py-3 rounded-lg bg-[#F57C00] hover:bg-[#e67300] text-white font-semibold transition cursor-pointer"
    >
        Login
    </button>
</form>