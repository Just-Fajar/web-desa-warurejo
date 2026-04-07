<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Desa Warurejo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-blue-100">

    <!-- Card Container -->
    <div class="w-full max-w-md px-4 animate-[fadeIn_.7s_ease]">
        
        <div class="bg-white/70 backdrop-blur-xl shadow-xl rounded-2xl border border-white/40">

            <!-- Header -->
            <div class="text-center py-8 border-b border-gray-200">
                <h1 class="text-3xl font-extrabold text-green-700">
                    ADMIN PANEL
                </h1>
                <p class="mt-1 text-gray-600">
                    Desa Warurejo
                </p>
            </div>

            <!-- Form -->
            <div class="p-8">

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 text-sm rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 text-sm rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="text-gray-700 font-semibold text-sm">Email</label>
                        <input 
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full mt-2 px-4 py-3 bg-white border border-gray-300 rounded-lg 
                                   text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-green-500 
                                   focus:border-green-500 transition"
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label for="password" class="text-gray-700 font-semibold text-sm">Password</label>
                        <input 
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full mt-2 px-4 py-3 bg-white border border-gray-300 rounded-lg 
                                   text-gray-700 placeholder-gray-400 focus:ring-2 focus:ring-green-500 
                                   focus:border-green-500 transition"
                        >
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center mb-6">
                        <input 
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                        >
                        <label for="remember" class="ml-2 text-gray-700 text-sm">Ingat saya</label>
                    </div>

                    <!-- Button -->
                    <button 
                        type="submit"
                        class="w-full py-3 rounded-lg bg-green-600 hover:bg-green-700 
                               text-white font-bold transition"
                    >
                        LOGIN
                    </button>
                </form>

                <!-- Back -->
                <div class="mt-6 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-blue-700 hover:text-blue-900 transition">
                        ← Kembali ke Website
                    </a>
                </div>
            </div>
        </div>

        <p class="text-center mt-4 text-gray-500 text-xs">
            © {{ date('Y') }} Desa Warurejo
        </p>
    </div>

    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>

</body>
</html>
