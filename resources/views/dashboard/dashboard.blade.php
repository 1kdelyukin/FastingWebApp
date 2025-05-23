@extends('layouts.app')


@push('meta')
    @vite([
        'resources/js/timer.js',

        'resources/css/jsCalendar.css',
    ])

@endpush
@section('title', 'Dashboard')

@section('content')


<div class="w-full h-full flex flex-col items-center justify-center">
<div class="container mx-auto h-1/2 mb-4">

<x-intermittent-fasting-timer 
    default-start-time="12:00"
    default-end-time="20:00"
/>

</div>
</div>
@endsection
