@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-qrcode mr-3"></i> QR Code Absensi
            </h2>
        </div>

        <div class="p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $event->title }}</h3>
            <p class="text-gray-500 mb-6">Scan QR Code ini untuk mengisi absensi</p>

            <div class="flex justify-center mb-8">
                <div class="p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm">
                    {{-- Generate QR Code SVG untuk tampilan layar --}}
                    {!! QrCode::size(250)->generate($url) !!}
                </div>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('event.qrcode.download', $event->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow transition flex items-center">
                    <i class="fas fa-download mr-2"></i> Unduh PNG
                </a>
                
                <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-6 rounded-lg shadow transition">
                    Kembali
                </a>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-3 text-center text-sm text-gray-500 border-t">
            Link: <a href="{{ $url }}" class="text-blue-500 hover:underline" target="_blank">{{ $url }}</a>
        </div>
    </div>
</div>
@endsection