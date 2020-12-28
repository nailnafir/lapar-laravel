<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request) {
        try {
            // validasi input
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // mengecek credentials (login)
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)) {
                return Response()->json([
                    'message' => 'Email atau password salah',
                ], 422);
            }

            // jika hash tidak sesuai, maka beri pesan error
            $user = User::where('email', $request->email)->first();
            if(!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Kesalahan data');
            }

            // jika berhasil, maka sekalian login
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Berhasil');
        } catch (Exception $errors) {
            return Response()->json([
                'message' => 'Ada kesalahan',
                'errors' => $errors->validator->errors(),
            ], 500);
        }
    }
}
