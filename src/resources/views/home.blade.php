@extends('layouts.authenticated')

@section('content')
<div class="border-b border-gray-800 px-4 py-3">
    <h1 class="text-xl font-bold text-white">Feed</h1>
</div>

    <div class="px-4 py-3">
        <create-post></create-post>

        <post-feed></post-feed>
    </div>
@endsection
