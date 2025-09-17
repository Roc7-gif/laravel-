@extends('base')

@section('title')
Les produits du blog  Blog 
@endsection


@section('content')
<h1>Produits du blog  </h1>
<br>
@foreach( $product as $p)
<div class="block">
<h2>
{{ $p->title }}
</h2>
<p>
{{ $p->content }}
</p>
<a href=""></a>
<a href="{{ route('blog.show' , ['product'=>$p-> id ])  }}"> Voir plus </a>
<hr>
</div>
@endforeach
@endsection