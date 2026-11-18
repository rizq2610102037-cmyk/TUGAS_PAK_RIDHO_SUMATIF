<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wajib Ganti Kata Sandi Awal - Portal PIJAR & SUAR</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAFCEB] font-sans text-gray-800 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-3xl max-w-md w-full p-7 shadow-lg border border-gray-100 space-y-5">
        <div class="text-center space-y-2">
            <span class="inline-block bg-[#FFF0E0] text-[#F57C00] text-[11px] font-extrabold uppercase px-3 py-1 rounded-full">
                🔒 KEAMANAN AKUN PERTAMA KALI
            </span>
            <h1 class="text-xl font-extrabold text-[#0A4D29]">Ganti Kata Sandi Awal</h1>
            <p class="text-xs text-gray-500">
                Halo <strong>{{ Auth::user()->name }}</strong>, akun Anda baru diterbitkan otomatis oleh sistem PIJAR & SUAR. Silakan buat kata sandi pribadi minimal 8 karakter sebelum melanjutkan.
            </p>
        </div>

        @if ($errors->any())
            <div class="bg-[#FADBD8] border border-[#C0392B] text-[#C0392B] px-3.5 py-2.5 rounded-xl text-xs font-bold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('password.force.update') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block font-bold text-gray-700 mb-1">Kata Sandi Baru (Minimal 8 Karakter)</label>
                <input type="password" name="password" required minlength="8" placeholder="Masukkan kata sandi baru..." class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0A4D29]">
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                <input type="password" name="password_confirmation" required minlength="8" placeholder="Ulangi kata sandi baru..." class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0A4D29]">
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-[#0A4D29] hover:bg-[#083b1f] text-white font-extrabold text-xs shadow-sm cursor-pointer">
                Simpan Kata Sandi & Masuk ke Portal &rarr;
            </button>
        </form>
    </div>

</body>
</html>