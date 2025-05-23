<?php
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Insights\WeeklyProgressChartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Dashboard\FastingController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\RecipePageController;
use App\Http\Controllers\RecipeController;

// Explore routes
Route::get('/explore', [ExploreController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('explore');
Route::get('/explore/article/{id}', [ExploreController::class, 'show'])->name('explore.show');
Route::get('/explore/filter', [ExploreController::class, 'filterByTag'])->name('explore.filter');
Route::get('/explore/healthy-foods', [ExploreController::class, 'healthyFoods'])->name('explore.healthy-foods');
Route::get('/explore/unhealthy-foods', [ExploreController::class, 'unhealthyFoods'])->name('explore.unhealthy-foods');
// Home Route
Route::middleware('guest')->get('/', function () {
    return view('welcome');
})->name('Login');

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit'); 
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store'); // store notes
    Route::get('/insights', [WeeklyProgressChartController::class, 'showProgress'])->name('insights'); // insights page route, please change to match requirements
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/recipes', [RecipePageController::class, 'show'])->name('recipesPage.show');
    Route::get('/recipes/{slug}', [RecipeController::class, 'show'])->name('recipes.show');

    // Post Routes
    Route::post('/fasting/save', [FastingController::class, 'store'])->name('fasting.save');

    // Get Routes
    Route::get('/fasting/last', [FastingController::class, 'getLast'])->name('fasting.last');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
});

// Authentication Routes
require __DIR__ . '/auth.php';
