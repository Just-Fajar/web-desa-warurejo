@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('breadcrumb')
    <li class="flex items-center text-gray-500 text-sm">
        <i class="fas fa-chevron-right mx-2 text-gray-400"></i> Profil Aktif
    </li>
@endsection

@section('content')

    <div class="container mx-auto px-4 py-6 max-w-5xl space-y-8">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Profil Anda</h1>
                <p class="text-sm text-gray-500 mt-1">Detail informasi dan aktivitas akun admin Anda</p>
            </div>
            <a href="{{ route('admin.profile.edit') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-medium transition-all shadow-sm">
                <i class="fas fa-user-edit mr-2"></i>
                Edit Profil
            </a>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KIRI: Foto & Badge -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden group">
                    <!-- Deco Background -->
                    <div
                        class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-primary-50 to-primary-100 z-0 group-hover:h-36 transition-all duration-300">
                    </div>

                    <!-- Avatar -->
                    <div class="relative z-10 mx-auto mb-5 w-36 h-36">
                        @if($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="{{ $admin->name }}"
                                class="w-full h-full rounded-full object-cover shadow-lg border-4 border-white transition-transform duration-300 group-hover:scale-105">
                        @else
                            <div
                                class="w-full h-full rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg border-4 border-white transition-transform duration-300 group-hover:scale-105">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="relative z-10 space-y-2">
                        <h3 class="text-xl font-bold text-gray-800">{{ $admin->name }}</h3>
                        <p class="text-sm font-medium text-primary-600">Administrator</p>
                    </div>

                    <div class="relative z-10 mt-6 pt-6 border-t border-gray-50">
                        <span
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-sm font-medium border border-emerald-100 shadow-sm">
                            Status Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- KANAN: Detail Info & Aktivitas -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Detail Kontak & Informasi -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                            <i class="fas fa-id-card text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Informasi Dasar
                        </h2>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">

                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 border-b border-gray-50 pb-6">
                            <div
                                class="w-40 text-sm font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-user mb-0.5"></i> Nama Lengkap
                            </div>
                            <div class="flex-1 text-gray-800 font-medium">
                                {{ $admin->name }}
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 border-b border-gray-50 pb-6">
                            <div
                                class="w-40 text-sm font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-envelope mb-0.5"></i> Email Address
                            </div>
                            <div class="flex-1 text-gray-800 font-medium">
                                {{ $admin->email }}
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-6">
                            <div
                                class="w-40 text-sm font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2">
                                <i class="fas fa-calendar-alt mb-0.5"></i> Tanggal Dibuat
                            </div>
                            <div class="flex-1 text-gray-800 font-medium">
                                {{ $admin->created_at->format('d F Y') }} <span
                                    class="text-gray-400 text-sm font-normal ml-2">({{ $admin->created_at->diffForHumans() }})</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

@endsection