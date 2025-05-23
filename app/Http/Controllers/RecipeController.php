<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Recipe;


class RecipeController extends Controller
{
    public function show($slug)
    {
        $recipe = Recipe::where('page_slug', $slug)->firstOrFail();
        return view('recipes.show', compact('recipe'));
    }
}
