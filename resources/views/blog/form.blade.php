@extends('base')

@section('title')
Mon Blog 
@endsection


@section('content')

<style>
    form{
        display: flex;
        max-width: 80vh;
        flex-wrap: wrap;
        gap: 40px;
        background-color: #f7f7f7;
        padding: 10px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.086);
        
    }
    input,textarea{
        display: block ;
        padding: 8px;
        margin: 5px;
        min-width: 90%;
        border-radius: 10px;
        border: 1px solid #db2a2ae1;
    }
    button[type='submit']{
        margin: auto;
        display: inline-block;
        padding: 8px;
        border-radius: 10px;
        background-color: #db2a2ae1;
        border: none;
        outline: none;
        cursor: pointer;
        color: white;

    }
    button[type='submit']:hover{
        transform: scale(1.004);
    }
</style>
<center>
<h1>Formulaire de creation d'un produit </h1>

<form action="" method="post" >
    <div>
    <input type="text" name="titre" id="" placeholder="Titre" name="titre">
        @error('titre')
        {{ $message }}
        @enderror
    </div>
    <div>
    <input type="text" name="slug" id="" name ='Accroche' placeholder="slug">
    @error('slug')
        {{ $message }}
        @enderror
    </div>
    @csrf
    <div>
    <textarea name="description" placeholder="description" id="" cols="30" rows="10"></textarea> <br> <br>
    @error('content')
        {{ $message }}
        @enderror
    </div>
        <button type="submit" > Soumettre</button>
        <a href="{{ route('blog.index') }}" > Retour au produits</a>
</form>
</center>
@endsection