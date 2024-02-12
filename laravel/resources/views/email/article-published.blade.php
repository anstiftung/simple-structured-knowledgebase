@extends('email.layout.default')

@section('content')
    <h3>Hallo,</h3>
    <p>dein Artikel <a href="{{ config('app.url') . '/beitrag/' . $article->slug; }}">{{ $article->title }}</a> wurde soeben auf dem Cowiki veröffentlicht.</p>
    <p>Liebe Grüße!</p>
@endsection
