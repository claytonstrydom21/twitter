@extends('layouts.authenticated')

@section('content')
    <div class="border-b border-gray-800 px-4 py-3">
        <h1 class="text-xl font-bold text-white">Notifications</h1>
    </div>

    <div class="bg-black text-white rounded-lg">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-800 p-4
                {{ !$notification->is_read ? 'bg-gray-900' : '' }}">
                <p>{{ $notification->content }}</p>
                <small class="text-gray-500">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                No notifications yet
            </div>
        @endforelse
    </div>
@endsection
