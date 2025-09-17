@extends('base')

@section('title')
{{$product->slug}} 
@endsection


@section('content')
<div class="product">
    
<h1>{{ $product-> title  }}</h1>
<p>
{{ $product->slug }}
</p>
<p>
{{ $product->content }}
</p>
<hr>
</div>
@endsection
