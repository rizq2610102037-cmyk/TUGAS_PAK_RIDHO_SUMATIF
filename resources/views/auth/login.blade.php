<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PIJAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    {{-- Background Split Screen (Kiri Putih, Kanan Oranye) --}}
    <div class="fixed inset-0 flex -z-10">
        <div class="w-1/2 bg-white"></div>
        <div class="w-1/2 bg-[#F57C00]"></div>
    </div>

    {{-- Card Utama Login --}}
    <div class="w-full max-w-4xl mx-4 rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row border-4 border-[#115E33]">
        
        {{-- Panel Kiri (Putih - Area Logo / Ilustrasi) --}}
        <div class="w-full md:w-1/2 bg-white p-10 flex flex-col items-center justify-center min-h-[200px] md:min-h-[480px]">
            {{-- Tempat logo Pradita / PIJAR jika ingin ditambahkan --}}
        </div>

        {{-- Panel Kanan (Hijau Tua - Form Login) --}}
        <div class="w-full md:w-1/2 bg-[#115E33] p-10 md:p-12 flex flex-col justify-center">
            <h1 class="text-4xl font-bold text-white mb-1">Login</h1>
            <p class="text-white/90 text-sm mb-8">Masuk ke akun PIJAR</p>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Pesan Error Validasi / Gagal Login --}}
                @if ($errors->any())
                    <div class="p-3 rounded-lg bg-red-500/20 border border-red-400 text-white text-xs">
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
                        class="w-full px-4 py-3 rounded-lg bg-transparent border border-[#F57C00] text-white placeholder-white/70 text-sm focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
                    >
                </div>

                <div>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Password" 
                        required
                        class="w-full px-4 py-3 rounded-lg bg-transparent border border-[#F57C00] text-white placeholder-white/70 text-sm focus:outline-none focus:ring-2 focus:ring-[#F57C00]"
                    >
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="w-4 h-4 rounded border-white/50 bg-transparent text-[#F57C00] focus:ring-[#F57C00]"
                    >
                    <label for="remember" class="text-white text-xs cursor-pointer">Keep me logged in</label>
                </div>

                <div class="pt-3">
                    <button 
                        type="submit" 
                        class="w-full py-3 rounded-lg bg-[#F57C00] hover:bg-[#e67300] text-white font-semibold text-sm transition cursor-pointer shadow-md"
                    >
                        Login
                    </button>
                </div>

                <div class="text-center pt-2">
                    <a href="#" class="text-white text-xs hover:underline">Forget password?</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>