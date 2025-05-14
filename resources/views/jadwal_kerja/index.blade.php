@extends('adminlte::page')

@section('title', 'Jadwal Kerja')

@section('content_header')
<h1>Jadwal Kerja</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@stop

@section('footer')
@include('footer')
@stop

@section('css')
<link href="https://unpkg.com/fullcalendar@6.1.17/main.min.css" rel="stylesheet">
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            events: '{{ route('jadwal.kerja.data') }}'
        });
        calendar.render();
    });
</script>
@endsection

