<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
     public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'Register berhasil',
            'user'    => $user
        ]);
    }

     public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Delete old tokens (opsional, biar 1 user hanya 1 sesi)
        $user->tokens()->delete();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login sukses',
            'token'   => $token,
            'user'    => $user
        ]);
    }

     public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function getUser(Request $request)
    {
        $user = $request->user();

        if ($user && $user->foto_profile) {
            $user->foto_profile = asset('storage/' . $user->foto_profile);
        }

        return response()->json([
            'user' => $user
        ]);
    }

    public function updateFotoProfile(Request $request)
    {
        $request->validate([
            'foto_profile' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = $request->user();

        // hapus foto lama (kecuali null)
        if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
            Storage::disk('public')->delete($user->foto_profile);
        }

        // simpan foto baru
        $path = $request->file('foto_profile')
            ->store('profile', 'public');

        $user->update([
            'foto_profile' => $path
        ]);

        return response()->json([
            'message' => 'Foto profile berhasil diperbarui',
            'foto_profile' => asset('storage/' . $path)
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password'     => ['required', 'min:6', 'different:current_password'],
            'confirm_password' => ['required', 'same:new_password'],
        ]);

        $user = $request->user();

        // cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Password lama tidak sesuai'
            ], 422);
        }

        // update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }


}
