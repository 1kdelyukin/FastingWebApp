@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
  <h1 class="text-3xl font-bold mb-6">All Recipes</h1>

  <!-- Search Bar -->
  <div class="w-full mb-6">
    <input
      type="text"
      id="recipeSearch"
      placeholder="Search for a recipe..."
      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-300"
    >
  </div>

  <!-- Recipes Grid -->
  <div id="recipesGrid" class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($recipes as $recipe)
      <a
        href="{{ url('/recipes/' . $recipe->page_slug) }}"
        class="block bg-white rounded-lg shadow-md overflow-hidden flex flex-col w-full hover:shadow-lg transition-shadow recipe-item"
        data-title="{{ strtolower($recipe->name) }}"
      >
        @if(!empty($recipe->cover_image))
          <img
            src="{{ asset($recipe->cover_image) }}"
            alt="{{ $recipe->name }}"
            class="w-full h-48 object-cover"
          >
        @endif

        <div class="p-4 flex-1 flex flex-col justify-center text-center">
          <h2 class="text-xl font-semibold mb-2">{{ $recipe->name }}</h2>
          <p class="inline-block bg-gray-100 text-gray-600 text-sm font-medium px-2 py-1 mb-2 rounded">
            <strong>Serves:</strong> {{ $recipe->serves }}
          </p>
          @if($recipe->anti_inflammatory)
            <span class="inline-block bg-green-100 text-green-800 text-sm font-medium px-2 py-1 rounded">
              Anti-Inflammatory
            </span>
          @endif
        </div>
      </a>
    @endforeach
  </div>
</div>

<!-- Script for Dynamic Search -->
<script>
  const searchInput = document.getElementById('recipeSearch')
  const recipeItems = document.querySelectorAll('.recipe-item')

  searchInput.addEventListener('input', () => {
    const q = searchInput.value.toLowerCase().trim()

    recipeItems.forEach(item => {
      const title = item.dataset.title

      if (title.includes(q)) {
        // Match → make it visible again by removing the 'hidden' class
        item.classList.remove('hidden')
      } else {
        // No match → remove it from the layout by adding the 'hidden' class
        item.classList.add('hidden')
      }
    })
  })
</script>
@endsection