<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Beasiswa Poliban</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FAF3DD] font-sans min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-4xl bg-[#FAF3DD] rounded-2xl overflow-hidden p-4 md:p-8">
        
        <div class="flex flex-col items-center text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-[#C13E3E] tracking-wide">Poliban Scholarship</h1>
            <p class="text-xs md:text-sm text-gray-600 mt-1 max-w-md">
                Sistem Informasi Pendaftaran Beasiswa Politeknik Negeri Banjarmasin
            </p>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-md p-6 md:p-8 mb-6">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-xs font-semibold text-gray-500 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="name@student.poliban.ac.id" 
                        class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-[#C13E3E] focus:ring-1 focus:ring-[#C13E3E]">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-5">
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-xs font-semibold text-gray-500">Password</label>
                    </div>
                    <input id="password" type="password" name="password" required placeholder="••••••••" 
                        class="w-full px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-[#C13E3E] focus:ring-1 focus:ring-[#C13E3E]">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-[#C13E3E] hover:bg-[#a63232] text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition duration-200">
                    Masuk
                </button>
            </form>

            <!-- LINK MENUJU DAFTAR AKUN -->
            <div class="text-center mt-5 text-xs text-gray-500">
                Belum punya akun? <a href="{{ route('register') }}" class="text-[#C13E3E] font-bold hover:underline">Daftar Sekarang</a>
            </div>
        </div>

    </div>
</body>
</html>