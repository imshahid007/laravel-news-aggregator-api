<?php

namespace App\Http\Controllers\Api\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as Password;
use Illuminate\Validation\Rules\Password as PasswordRules;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
            'device_name' => ['required'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($request->device_name)->plainTextToken;
        //
        return response()->json([
            'message' => 'User created successfully',
            'token'   => $token,
            'user'    => $user,

        ], 201);
    }
    // Login
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = auth()->user();
        // Create a token for the user
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,

        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
		$request->user()->currentAccessToken()->delete();
        //
        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    // Get the authenticated user
    public function getAuthenticatedUser(Request $request) {
		return $request->user();
	}
    // Reset password link
	public function sendPasswordResetLinkEmail(Request $request) {
		$request->validate(['email' => ['required', 'email', 'exists:users']]);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if($status === Password::RESET_LINK_SENT) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
    // Reset password
	public function resetPassword(Request $request) {
		$request->validate([
			'token' => 'required',
			'email' => ['required', 'email','exists:users'],
            'password' => ['required', 'confirmed', PasswordRules::defaults()],
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) use ($request) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();

			}
		);

		if($status == Password::PASSWORD_RESET) {
			return response()->json(['message' => __($status)], 200);
		} else {
			throw ValidationException::withMessages([
				'email' => __($status)
			]);
		}
	}
}
