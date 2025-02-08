@extends('layouts.authenticated')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <profile-view
            :user="{{ json_encode($user) }}"
            :posts="{{ json_encode($posts) }}"
            :current-user-id="{{ auth()->id() }}"
        ></profile-view>
    </div>
@endsection
