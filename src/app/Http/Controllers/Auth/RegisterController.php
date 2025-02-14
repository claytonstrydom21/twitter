<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar' => ['nullable', 'file', 'image', 'mimes:jpeg,png,gif,webp', 'max:5120']
        ]);

        $registrationData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        if ($request->hasFile('avatar')) {
            $avatarFile = $request->file('avatar');

            $avatarPath = $avatarFile->store('avatars', 'public');

            $registrationData['avatar'] = $avatarPath;
        }

        $this->authService->register($registrationData);

        return response()->json(['redirect' => route('set-username')]);
    }
}
