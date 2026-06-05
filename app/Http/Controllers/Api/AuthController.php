<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(responseFailed($validator->errors()->first()), 422);
        }

        $credentials = $request->only(['username', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(responseFailed('Username atau Password salah'), 401);
        }

        $user = auth('api')->user();
        if ($user->hasRole('REGISTERED_USER') && !$user->hasVerifiedEmail()) {
            auth('api')->logout();
            return response()->json(responseFailed('Email Anda belum diverifikasi. Silakan cek email Anda.'), 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'nowa' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(responseFailed($validator->errors()->first()), 422);
        }

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'nowa' => $request->nowa,
            ]);

            // Assign default REGISTERED_USER role
            $user->assignRole('REGISTERED_USER');

            // Create empty profile
            $user->profile()->create([
                'display_name' => $user->name,
            ]);

            // Send email verification notification
            $user->sendEmailVerificationNotification();

            \DB::commit();

            return response()->json(responseSuccess([], 'Registrasi berhasil! Silakan periksa email Anda untuk verifikasi.'));
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(responseFailed('Gagal melakukan registrasi: ' . $e->getMessage()), 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(responseSuccess(auth('api')->user()));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(responseSuccess([], 'Successfully logged out'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json(responseSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ], 'Login Berhasil'));
    }
}
