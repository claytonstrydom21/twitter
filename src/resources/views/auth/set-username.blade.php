@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div id="app">
            <set-username-modal :show="true"></set-username-modal>
        </div>
    </div>
@endsection
