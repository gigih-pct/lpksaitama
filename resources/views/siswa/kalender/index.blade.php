@extends('siswa.app')

@section('title', 'Kalender Akademik - LPK Saitama')

@section('content')
<!-- FullCalendar via CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<div class="max-w-6xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up">
    <!-- Header -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-medium text-primer">Kalender Akademik & Jadwal</h1>
            <p class="text-sm text-gray-500 mt-1">Lihat jadwal harian dan agenda akademik LPK Saitama.</p>
        </div>
        <a href="{{ route('siswa.pembelajaran') }}" class="text-sm font-medium text-gray-500 hover:text-primer border border-gray-200 px-4 py-2 rounded-md transition-colors flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white rounded-md border border-gray-100 shadow-sm p-6">
        <div id="calendar" class="w-full h-[600px] font-sans"></div>
    </div>
</div>

<style>
    /* FullCalendar custom styles to match theme */
    .fc { font-family: 'Poppins', sans-serif; }
    .fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 500; color: #18345C; }
    .fc .fc-button-primary { background-color: #18345C; border-color: #18345C; }
    .fc .fc-button-primary:hover { background-color: #102643; border-color: #102643; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #0d2038; border-color: #0d2038; }
    .fc .fc-col-header-cell-cushion { color: #6b7280; font-weight: 500; font-size: 0.875rem; padding: 0.5rem; }
    .fc .fc-daygrid-day-number { color: #374151; font-weight: 500; font-size: 0.875rem; padding: 0.5rem; }
    .fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid { border-color: #f3f4f6; }
    .fc .fc-day-today { background-color: rgba(0, 153, 217, 0.05) !important; }
    .fc-event { border: none; padding: 2px 4px; border-radius: 4px; font-size: 0.75rem; font-weight: 500; cursor: pointer; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        // Prepare events data from PHP
        var events = [];
        
        // 1. Weekly Schedules (Jadwal)
        @php
            $hariMap = [
                'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
            ];
        @endphp
        
        @foreach($jadwals as $jadwal)
            events.push({
                title: '{{ $jadwal->kegiatan }}',
                startTime: '{{ $jadwal->jam_mulai }}',
                endTime: '{{ $jadwal->jam_selesai }}',
                daysOfWeek: [{{ $hariMap[$jadwal->hari] }}],
                backgroundColor: '#18345C', // primer
                textColor: '#ffffff'
            });
        @endforeach
        
        // 2. Specific Academic Calendar Events
        @foreach($kalenders as $kalender)
            events.push({
                title: '{{ $kalender->kegiatan }} ({{ $kalender->jenis }})',
                start: '{{ \Carbon\Carbon::parse($kalender->tanggal_mulai)->format("Y-m-d") }}',
                @if($kalender->tanggal_selesai)
                end: '{{ \Carbon\Carbon::parse($kalender->tanggal_selesai)->addDay()->format("Y-m-d") }}',
                @endif
                backgroundColor: '{{ $kalender->jenis == "Libur" ? "#ef4444" : "#0099D9" }}', // red / tersier
                textColor: '#ffffff',
                allDay: true
            });
        @endforeach

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu'
            },
            events: events,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false,
                hour12: false
            }
        });
        
        calendar.render();
    });
</script>
@endsection
