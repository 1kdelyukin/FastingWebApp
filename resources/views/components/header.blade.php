@php
  // Default padding
  $paddingClass = 'px-9';

  if (request()->is('recipes')) {
    $paddingClass = 'pl-5 pr-6'; // Smaller padding for /recipes
  } elseif (request()->is('recipes/*')) {
    $paddingClass = 'pl-5 pr-6'; // Larger padding for recipe details
  } elseif (request()->routeIs('mealinfo') || request()->is('explore/*')) {
    $paddingClass = 'px-9'; // Default padding for explore pages
  } elseif (request()->is('explore')) {
    $paddingClass = 'px-9'; // Default padding for /explore
  } else {
    $paddingClass = 'px-9'; // Tight padding for other pages
  }
@endphp

<header class="w-full flex justify-between items-center {{ $paddingClass }} pt-9 pb-6 bg-white">
  <div class="text-2xl font-bold flex items-center">
    {{-- 1) Exactly /recipes: back-arrow + "Explore" --}}
    @if(request()->is('recipes'))
      <a href="{{ url('/explore') }}" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span>Explore</span>
      </a>

    {{-- 2) Any recipe detail: back-arrow + "Recipes" --}}
    @elseif(request()->is('recipes/*'))
      <a href="{{ route('recipesPage.show') }}" class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        <span>Recipes</span>
      </a>

    {{-- 3) Exactly /explore: show logo + "Explore" --}}
    @elseif(request()->is('explore'))
      <img src="{{ asset('images/THINKFast_app_logo.svg') }}" alt="THINKFast Logo" class="h-12 mr-3">
      <span>Explore</span>

    {{-- 4) Your existing "Explore" pages (subpages) --}}
    @elseif(request()->routeIs('mealinfo') || request()->is('explore/*'))
      Explore

    {{-- 5) Everything else: today's date --}}
    @else
      {{ now()->format('F j, Y') }}
    @endif
  </div>

  <div class="flex items-center">
    @auth
      <a href="{{ route('profile.edit') }}" class="h-12 w-12 rounded-full overflow-hidden border-2 border-customGray flex items-center justify-center bg-gray-200">
        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="h-full w-full object-cover">
      </a>
    @else
      <a href="{{ route('login') }}" class="h-12 w-12 rounded-full overflow-hidden border-2 border-customGray flex items-center justify-center bg-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
      </a>
    @endauth
  </div>
</header>