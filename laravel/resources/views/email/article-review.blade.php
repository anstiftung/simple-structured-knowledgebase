@extends('email.layout.default')

@section('content')
    <h3>Hallo,</h3>
    <p>ein neuer Artikel <a href="{{ config('app.url') . '/beitrag/' . $article->slug; }}">{{ $article->title }}</a> wurde soeben für den Reviewprozess freigegeben.</p>
    <p>Liebe Grüße!</p>
@endsection
