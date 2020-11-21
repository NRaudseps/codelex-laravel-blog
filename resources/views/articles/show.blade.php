@extends('layouts.app')
@section('content')
    <div>
        <h4><strong>{{ $article->title }}</strong></h4>
        <p>{{ $article->content }}</p>
    </div>
@endsection
