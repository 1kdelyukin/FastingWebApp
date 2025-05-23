<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipePageController extends Controller
{
    /**
     * Display a listing of all recipes.
     *
     * Only the fields needed for the card list
     * are selected to optimize the query.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Only fetch the columns we actually display
        $recipes = Recipe::select([
            'name',
            'cover_image',         // cover_image column in your DB
            'serves',
            'anti_inflammatory',
            'page_slug',
        ])->get();

        return view('recipesPage.show', compact('recipes'));
    }
}
