<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SetUsernameController extends Controller
{
    public function show()
    {
        return view('auth.set-username');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:15', 'unique:users,username', 'alpha_dash']
        ]);

        auth()->user()->update([
            'username' => $validated['username']
        ]);

        return response()->json(['redirect' => route('home')]);
    }

    public function generateSuggestions()
    {
        $name = auth()->user()->name;
        $suggestions = [];

        // Generate 5 suggestions
        for ($i = 0; $i < 5; $i++) {
            $suggestions[] = Str::lower(str_replace(' ', '', $name) . rand(1000, 9999));
        }

        return response()->json($suggestions);
    }
}
