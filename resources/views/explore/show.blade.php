@extends('layouts.explore')

@section('content')
<div class="flex flex-col items-center justify-center pb-20 p-2">
    <div class="w-full max-w-4xl mx-auto p-4 space-y-6">
        <!-- Article Details -->
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="mb-4">
                <img src="{{ asset($article->article_thumbnail) }}" alt="{{ $article->article_title }}" 
                     class="w-full h-64 object-cover rounded-lg">
            </div>
            
            <h1 class="text-2xl font-bold mb-2">{{ $article->article_title }}</h1>
            
            <div class="mb-4">
                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-lg text-sm">
                    {{ $article->article_tag }}
                </span>
            </div>
            
            <div class="prose max-w-none">
                {!! nl2br(e($article->article_info)) !!}
                
                @if($article->article_title == 'What is Intermittent Fasting and its Benefits?')
                    <!-- 16:8 Diagram -->
                    <div class="my-6 text-center">
                        <img src="{{ asset('images/intermittent_fasting_.png') }}" alt="16:8 Intermittent Fasting Diagram" 
                             class="mx-auto rounded-lg shadow-md">
                    </div>
                    
                    <!-- Benefits Grid -->
                    <div class="flex flex-col md:flex-row md:flex-wrap md:justify-center gap-3 my-8 p-6">
                        <h3 class="w-full">
                            <span class="text-lg font-semibold">Benefits of Intermittent Fasting:</span>
                        </h3>
                        
                        <!-- First row -->
                        <div class="md:flex md:gap-4 md:justify-center w-full">
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/fat_loss.png') }}" alt="Fat Loss Benefits" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                            
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/blood_sugar.png') }}" alt="Blood Sugar Control" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                        </div>
                        
                        <!-- Second row -->
                        <div class="md:flex md:gap-4 md:justify-center w-full">
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/brain_health.png') }}" alt="Brain Health Benefits" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                            
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/anti_inflammatory.png') }}" alt="Anti-inflammation Benefits" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                        </div>
                    </div>
                @endif


     @if($article->article_title == 'What is an Anti-inflammatory Diet?')
                    
                    <!-- Benefits Grid -->
                    <div class="flex flex-col md:flex-row md:flex-wrap md:justify-center gap-3 my-8 p-6">
                        <h3 class="w-full">
                            <span class="text-lg font-semibold">Benefits</span>
                        </h3>
                        
                        <!-- First row -->
                        <div class="md:flex md:gap-4 md:justify-center w-full">
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/gut_microbiota_bf.png') }}" alt="Fat Loss Benefits" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                            
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/anti_inflammation_bf.png') }}" alt="Blood Sugar Control" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                        </div>
                        
                        <!-- Second row -->
                        <div class="md:flex md:gap-4 md:justify-center w-full">
                            <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                                <img src="{{ asset('images/blood_pressure_bf.png') }}" alt="Brain Health Benefits" 
                                     class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">
                            </div>
                        </div>


                    <h3 class="w-full">
                            <span class="text-lg font-semibold">Summary</span>
                    </h3>
                    <div class="text-center p-4 rounded-lg md:w-1/2 lg:w-2/5">
                        <img src="{{ asset('images/af_diet_sm.png') }}" alt="Fat Loss Benefits" 
                             class="w-50 h-50 md:w-60 md:h-60 rounded-xl mx-auto">

                    </div>


                @endif




            </div>
        </div>
        
        <!-- Related Articles -->
        @if(count($relatedArticles) > 0)
        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-lg font-semibold mb-3">Related Articles</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($relatedArticles as $relatedArticle)
                <a href="{{ url('/explore/article/' . $relatedArticle->article_id) }}" 
                   class="block border rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                    <img src="{{ asset($relatedArticle->article_thumbnail) }}" alt="{{ $relatedArticle->article_title }}" 
                         class="w-full h-32 object-cover">
                    <div class="p-3">
                        <h3 class="text-sm font-medium">{{ $relatedArticle->article_title }}</h3>
                        <div class="mt-2">
                            <span class="text-xs text-gray-500">{{ $relatedArticle->article_tag }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="mt-4">
            <a href="{{ route('explore') }}" class="inline-flex items-center text-customGreen hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back to Explore
            </a>
        </div>
    </div>
</div>
@endsection