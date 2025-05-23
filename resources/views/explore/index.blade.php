@extends('layouts.explore')

@push('meta')
    @vite(['resources/js/explore.js'])
@endpush


@section('content')
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<x-header />


<div class="flex flex-col items-center justify-center pb-20 p-2">
    <div class="w-full max-w-4xl mx-auto p-4 space-y-6">
        <!-- Your sections will now have 1.5rem (24px) spacing between them -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-lg font-semibold mb-3">Top Articles</h2>
            <div id="explore-content" class="space-y-3">
                <!-- Content will be loaded here via JavaScript -->
                <div class="overflow-x-auto whitespace-nowrap no-scrollbar -mx-4 px-4 pb-2">
            <!-- Card 1 -->
            <div class="inline-block w-64 mr-4 rounded-lg overflow-hidden shadow-md">
            <a href="{{ route('explore.healthy-foods') }}"><img src="../../images/healthyfoodsArticle.png" alt="Healthy Foods" class="w-full h-40 object-cover" />
            </div></a>

            <!-- Card 2 -->
            <div class="inline-block w-64 mr-4 rounded-lg overflow-hidden shadow-md">
            <a href="{{ route('explore.unhealthy-foods') }}"> <img src="../../images/unhealthyFoods.png" alt="Foods to Avoid" class="w-full h-40 object-cover" /></a>
            </div>
        </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 gap-y-2">
            <h2 class="text-lg font-semibold mb-3">Featured Recipe</h2>
            @if(isset($featuredRecipe))
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="w-full md:w-1/3">
                        <img src="{{ asset($featuredRecipe->cover_image) }}" alt="{{ $featuredRecipe->name }}" 
                             class="w-full h-48 object-cover rounded-lg">
                    </div>
                    <div class="w-full md:w-2/3">
                        <h3 class="text-xl font-medium">{{ $featuredRecipe->name }}</h3>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-lg text-sm">
                                Serves: {{ $featuredRecipe->serves }}
                            </span>
                            @if($featuredRecipe->anti_inflammatory)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm">
                                    Anti-inflammatory
                                </span>
                            @endif
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-sm text-gray-700">
                                {{ Str::limit(implode(', ', $featuredRecipe->ingredients), 100) }}
                            </p>
                        </div>
                        
                        <div class="mt-4 flex justify-end">
                            <a href="{{ url('/recipes/' . $featuredRecipe->page_slug) }}" 
                               class="inline-block px-4 py-2 bg-customGreen text-white rounded-lg hover:bg-opacity-90 transition-colors">
                                View Recipe
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center text-gray-500 py-6">No featured recipe available.</p>
            @endif
        </div>

<!-- All Recipes section -->
<div class="bg-white rounded-lg shadow-md p-4">
  <h2 class="text-lg font-semibold mb-3">All Recipes</h2>

  <!-- Horizontal Scroll Wrapper -->
  <div class="overflow-x-auto whitespace-nowrap no-scrollbar -mx-4 px-4 pb-2">
    @forelse($recipes as $recipe)
      <div class="inline-block w-64 mr-4 border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
        <img src="{{ asset($recipe->cover_image) }}" alt="{{ $recipe->name }}" class="w-full h-32 object-cover">
        <div class="p-3">
          <h3 class="text-sm font-medium">{{ $recipe->name }}</h3>
          <div class="mt-2 flex justify-between items-center">
            <span class="text-xs text-gray-500">Serves: {{ $recipe->serves }}</span>
            <a href="{{ url('/recipes/' . $recipe->page_slug) }}" class="text-xs text-customGreen hover:underline">View</a>
          </div>
        </div>
      </div>
    @empty
      <p class="text-center text-gray-500">No recipes available.</p>
    @endforelse
  </div>

  <div class="mt-3 text-right">
    <a href="{{ route('recipesPage.show') }}" class="inline-block px-4 py-2 bg-customGreen text-white rounded-lg hover:bg-opacity-90 transition-colors">Show All</a>
  </div>
</div>


<!-- Filter by Tag section -->
<div class="bg-white rounded-lg shadow-md p-4 gap-y-2">
    <h2 class="text-lg font-semibold mb-3">Filter Article by Tag</h2>
    
    <!-- Tag navigation -->
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="#" class="tag-filter bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded transition-colors" 
           data-tag="all">All</a>
        
        @foreach($tags as $tag)
            <a href="#" class="tag-filter bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded transition-colors" 
               data-tag="{{ $tag }}">{{ $tag }}</a>
        @endforeach
    </div>
    
    <!-- Filtered articles container -->
    <div id="filtered-articles" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
        @foreach($articles as $article)
            <div class="filtered-article border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow" 
                 data-tag="{{ $article->article_tag }}">
                <img src="{{ asset($article->article_thumbnail) }}" alt="{{ $article->article_title }}" 
                     class="w-full h-32 object-cover">
                <div class="p-3">
                    <h3 class="text-sm font-medium">{{ $article->article_title }}</h3>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-xs text-gray-500">{{ $article->article_tag }}</span>
                        <a href="{{ url('/explore/article/' . $article->article_id) }}" 
                           class="text-xs text-customGreen hover:underline">Read more</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

    </div>
</div>


