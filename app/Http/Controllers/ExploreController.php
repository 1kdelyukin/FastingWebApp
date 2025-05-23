<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;

class ExploreController extends Controller
{
    /**
     * Show the explore page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Your existing code for articles and tags
        $articles = DB::table('articles')
            ->select('article_id', 'article_title', 'article_thumbnail', 'article_tag')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $tags = DB::table('articles')
            ->select('article_tag')
            ->distinct()
            ->whereNotNull('article_tag')
            ->pluck('article_tag')
            ->toArray();
            
        $featuredArticles = DB::table('articles')
            ->select('article_id', 'article_title', 'article_thumbnail', 'article_tag')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Get all recipes for the carousel
        $recipes = \App\Models\Recipe::all();
        
        // Get a specific featured recipe (for example, ID 1 or a recipe with anti_inflammatory=true)
        // Option 1: Get a specific recipe by ID
        $featuredRecipe = \App\Models\Recipe::find(1); // Replace 1 with the desired recipe ID
        
        // Option 2: Get a random anti-inflammatory recipe
        // $featuredRecipe = \App\Models\Recipe::where('anti_inflammatory', true)->inRandomOrder()->first();
        
        // Option 3: Get the newest recipe
        // $featuredRecipe = \App\Models\Recipe::latest()->first();
            
        return view('explore.index', [
            'articles' => $articles,
            'tags' => $tags,
            'featuredArticles' => $featuredArticles,
            'recipes' => $recipes,
            'featuredRecipe' => $featuredRecipe
        ]);
    }
    public function healthyFoods() {
        return view('explore.healthy-foods');
    }

    public function unhealthyFoods() {
        return view('explore.unhealthy-foods');
    }

    public function show($id) 
    {
        $article = DB::table('articles')
            ->where('article_id', $id)
            ->first();
        if (!$article) {
            return redirect()->route('explore')->with('error', 'Article not found');
        }

        $relatedArticles = [];
        if ($article->article_tag) {
            $relatedArticles = DB::table('articles')
                ->where('article_tag', $article->article_tag)
                ->where('article_id', '!=', $id)
                ->limit(3)
                ->get();
        }

        return view('explore.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles
        ]);
    }
    
    public function filterByTag(Request $request)
    {
        $tag = $request->input('tag');
        
        $query = DB::table('articles')
            ->select('article_id', 'article_title', 'article_thumbnail', 'article_tag');
            
        if ($tag && $tag != 'all') {
            $query->where('article_tag', $tag);
        }
        
        $filteredArticles = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'articles' => $filteredArticles
        ]);
    }
}
