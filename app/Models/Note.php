<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Add this import

// Remove this line - it doesn't belong in a model file:
// Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');

class Note extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', // Foreign key to users table,
        'notes', // Stores the actual note itself,
        'date' // Date that the note is stored
    ];
}