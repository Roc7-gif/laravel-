<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogCreationRequest;
use App\Models\products;
use Illuminate\View\View; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    function index (): View {
        $product = products::all();
        return view('blog.blogIndex',['product' => $product]) ; 
    }
    function show (products $product) {

        return view('blog.product', ['product' => $product]);
       }
   function creationForm(){
    // 
        return view("blog.form");
   }
   function createproduct(BlogCreationRequest $request){
        $data = $request->validated(); 
        $product = products::create([
            'title' => $data['titre'],
            'content' => $data['description'],
            'slug' => $data['slug'],    
        ]);
        return  view ('blog.product' , ['product' =>$product]);
   }
   
}
