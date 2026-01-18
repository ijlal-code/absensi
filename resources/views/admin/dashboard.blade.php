@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="mb-10 text-center md:text-left">
        <h2 class="text-3xl font-bold text-gray-800">Dashboard Admin</h2>
        <p class="text-gray-500 mt-2">Selamat Datang di Sistem Absensi & Data Karyawan.</p>
    </div>

    {{-- Menu Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">

        {{-- CARD 1: MENU MANAJEMEN AGENDA (Tetap Link Utuh) --}}
        <a href="{{ route('event.agenda') }}" class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border-l-8 border-blue-600 overflow-hidden relative p-8 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div class="p-4 bg-blue-100 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-calendar-check fa-2x"></i>
                    </div>
                    <div class="text-right">
                        <span class="block text-4xl font-extrabold text-gray-800">{{ $totalEvents }}</span>
                        <span class="text-xs text-gray-500 uppercase tracking-wider">Total Agenda</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 group-hover:text-blue-600 transition">Manajemen Agenda</h3>
                    <p class="text-gray-500">Kelola jadwal rapat, buat agenda baru, QR Code, dan monitoring kehadiran.</p>
                </div>
            </div>
            
            {{-- Indikator Klik --}}
            <div class="mt-6 flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                <span>Buka Menu Agenda</span> <i class="fas fa-arrow-right ml-2"></i>
            </div>
        </a>

        {{-- CARD 2: MENU DATA KARYAWAN (Diubah menjadi Container dengan 3 Tombol) --}}
        <div class="bg-white rounded-2xl shadow-md border-l-8 border-green-600 overflow-hidden relative p-8 flex flex-col">
            
            {{-- Header Card --}}
            <div class="flex items-center justify-between mb-6">
                <div class="p-4 bg-green-100 rounded-2xl text-green-600">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <div class="text-right">
                    <span class="block text-4xl font-extrabold text-gray-800">{{ $totalEmployees }}</span>
                    <span class="text-xs text-gray-500 uppercase tracking-wider">Personil</span>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-1">Data Karyawan</h3>
                <p class="text-gray-500 text-sm">Pilih menu pengelolaan di bawah ini:</p>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-1 gap-3 mt-auto">
                
                {{-- 1. Kelola Data --}}
                <a href="{{ route('employee-management.index') }}" class="flex items-center justify-between px-4 py-3 bg-green-50 text-green-700 rounded-lg hover:bg-green-600 hover:text-white transition group">
                    <div class="flex items-center font-bold">
                        <i class="fas fa-edit w-6"></i> Kelola Data
                    </div>
                    <i class="fas fa-chevron-right opacity-50 group-hover:opacity-100 transition"></i>
                </a>

                {{-- 2. Informasi (View Only) --}}
                <a href="{{ route('employees.index') }}" class="flex items-center justify-between px-4 py-3 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-600 hover:text-white transition group">
                    <div class="flex items-center font-bold">
                        <i class="fas fa-info-circle w-6"></i> Informasi Karyawan
                    </div>
                    <i class="fas fa-chevron-right opacity-50 group-hover:opacity-100 transition"></i>
                </a>

                {{-- 3. Statistik --}}
                <a href="{{ route('employee-management.stats') }}" class="flex items-center justify-between px-4 py-3 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-600 hover:text-white transition group">
                    <div class="flex items-center font-bold">
                        <i class="fas fa-chart-pie w-6"></i> Statistik Visual
                    </div>
                    <i class="fas fa-chevron-right opacity-50 group-hover:opacity-100 transition"></i>
                </a>

            </div>
        </div>

    </div>
</div>
@endsection