<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function fetch(Request $request) {
        return ResponseFormatter::success($request->user(), 'Berhasil ambil data user');
    }

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

            // jika berhasil, masuk login
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Berhasil');
        } catch (Exception $errors) {
            return Response()->json([
                'message' => 'Data yang diberikan tidak sesuai',
                'errors' => $errors->validator->errors(),
            ], 500);
        }
    }

    public function register(Request $request) {
        try {
            // validasi input
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => $this->passwordRules(),
                'address' => 'required|string',
                'houseNumber' => 'required|string|max:255',
                'rtrw' => 'required|string|max:255',
                'subDistrict' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'phoneNumber' => 'required|string|max:255',
            ]);

            // buat user baru
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'address' => $request->address,
                'houseNumber' => $request->houseNumber,
                'rtrw' => $request->rtrw,
                'subDistrict' => $request->subDistrict,
                'district' => $request->district,
                'city' => $request->city,
                'phoneNumber' => $request->phoneNumber,
                'password' => Hash::make($request->password),
                'current_team_id' => 1,
            ]);

            // buatkan user baru token untuk masuk login
            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Pengguna Berhasil Terdaftar');
        } catch (Exception $errors) {
            return Response()->json([
                'message' => 'Data yang diberikan tidak sesuai',
                'errors' => $errors->validator->errors(),
            ], 422);
        }
    }

    public function logout(Request $request) {

        // cari user yang login, lalu delete tokennya
        $token = $request->user()->currentAccessToken()->delete();

        // menghasilkan boolean
        return ResponseFormatter::success($token, 'Token dihapus');
    }

    public function updateProfile(Request $request) {

        // ambil dan tampung semua data yang diminta
        $data = $request->all();

        // panggil user yang sedang login lalu update data
        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Berhasil update profil');
    }

    public function updatePhoto(Request $request) {

        // memanggil validator untuk mengecek syarat file
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048',
        ]);

        // jika validator gagal akan memberi pesan error
        if($validator->fails()) {
            return Response()->json([
                'errors' => $validator->errors(),
                'Gagal memperbarui foto profil',
                401
            ]);
        }

        // pengecekan file pada storage dan harus symlink dulu
        if($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            // simpan url foto ke database, fotonya disimpan pada storage laravel
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file], 'Berhasil upload file');
        }
    }
}
