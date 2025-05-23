{{-- resources/views/recipes/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center space-y-6">

    {{-- IMAGE (now max-w-lg to match the other cards) --}}
    <div class="w-full max-w-lg bg-white rounded-lg shadow-md overflow-hidden">
        <img 
            src="{{ asset($recipe->image) }}" 
            alt="{{ $recipe->name }}" 
            class="w-full h-64 object-cover"
        >
    </div>

    {{-- TITLE --}}
    <div class="w-full max-w-lg text-center">
        <h1 class="text-2xl font-bold">{{ $recipe->name }}</h1>
    </div>

    {{-- INGREDIENTS --}}
    <ul class="w-full max-w-lg bg-gray-100 rounded-lg shadow-md p-6 list-none">
        <h2 class="text-2xl font-semibold mb-4">Ingredients:</h2>
        @foreach($recipe->ingredients as $ingredient)
            <li class="flex items-start mb-2">
                <span class="font-bold mr-2 text-xl">•</span>
                <span>{{ $ingredient }}</span>
            </li>
        @endforeach
    </ul>

    {{-- DIRECTIONS --}}
    <ol class="w-full max-w-lg bg-gray-100 rounded-lg shadow-md p-6 list-none">
        <h2 class="text-2xl font-semibold mb-4">Directions:</h2>
        @foreach($recipe->directions as $index => $step)
            <li class="mb-2 flex">
                <span class="font-bold mr-2">{{ $index + 1 }})</span>
                <span>{{ $step }}</span>
            </li>
        @endforeach
    </ol>

</div>
@endsection
