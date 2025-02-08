@extends('layouts.authenticated')

@section('content')
    <div class="border-b border-gray-800 px-4 py-3">
        <h1 class="text-xl font-bold text-white">Feed</h1>
    </div>

    <div class="px-4 py-3">
        <feed-container
            :current-user-id="{{ (int)auth()->id() }}"
        ></feed-container>
    </div>
@endsection
