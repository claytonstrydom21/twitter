@extends('layouts.authenticated')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <profile-edit
            :user="{{ json_encode(auth()->user()) }}"
        ></profile-edit>
    </div>
@endsection
