@extends('layouts.authenticated')

@section('content')
    <div class="border-b border-gray-800 px-4 py-3">
        <h1 class="text-xl font-bold text-white">Explore</h1>
    </div>

    <div class="px-4 py-3">
        <explore-page :current-user-id="{{ auth()->id() }}"></explore-page>
    </div>
@endsection
