<?php

use App\Http\Controllers\AnonymizedMatricules;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
Route::get('/', function (): View {
    return view('home');
});
Route::get('/matricule', [AnonymizedMatricules::class, 'index'])->name('matricule.upload');
Route::post('/matricule/upload', [AnonymizedMatricules::class, 'upload'])->name('matricule.process');
Route::get('/matricule/search', [AnonymizedMatricules::class, 'search'])->name('matricule.search');

Route::prefix('/blog', )->controller(BlogController::class )->group(function(){
   
    Route::get('/',  'index')->name('blog.index');
    Route::get('/myproduct/{product}',  'show')->name('blog.show')-> where([
        'id'=>  '[0-9]+'
    ]);

//    CREATION
    Route::get('/addproduct',  'creationForm')->name('blog.creationForm');
    Route::post('/addproduct',  'createproduct');
//------------

}); 