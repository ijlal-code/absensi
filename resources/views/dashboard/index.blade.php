@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Acara</h2>
    <a href="{{ route('event.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 text-sm">
        <i class="fas fa-plus"></i> Buat Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if($todayEvents->isEmpty())
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-calendar-times text-4xl mb-3 text-gray-300"></i>
            <p>Tidak ada acara hari ini.</p>
        </div>
    @else
        <div class="grid gap-0 divide-y">
            @foreach($todayEvents as $event)
            <div class="p-4 md:flex justify-between items-center hover:bg-gray-50">
                <div class="mb-4 md:mb-0">
                    <h4 class="font-bold text-lg text-blue-900">{{ $event->title }}</h4>
                    <p class="text-sm text-gray-600">
                        <i class="far fa-clock mr-1"></i> {{ $event->start_time }} - {{ $event->end_time }}
                        <span class="mx-2 text-gray-300">|</span>
                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $event->location }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('event.show', $event->id) }}" class="bg-green-100 text-green-700 px-3 py-1 rounded border border-green-200 text-sm hover:bg-green-200">
                        <i class="fas fa-eye"></i> Monitor
                    </a>
                    
                    <a href="{{ route('event.edit', $event->id) }}" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded border border-yellow-200 text-sm hover:bg-yellow-200">
                        <i class="fas fa-edit"></i> Edit
                    </a>

                    <form action="{{ route('event.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Yakin hapus acara ini?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-700 px-3 py-1 rounded border border-red-200 text-sm hover:bg-red-200">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection