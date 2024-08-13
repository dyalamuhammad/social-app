<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login() {
        return view("auth.login");
    }
    public function register() {
        return view("auth.register");
    }
    public function resetPassword() {
        return view("auth.reset-password");
    }
    public function doReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            return response()->json(['success' => true, 'email' => $user->email]);
        }

        return response()->json(['success' => false, 'message' => 'Email not found.']);
        
    }

    public function newPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|confirmed',
            ]);
    
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
    
            return redirect()->route('login')->with('success', 'Password has been reset successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal ubah group. Error: ' . $e->getMessage());
        }
    }

    public function doLogin(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required',
                'password' => 'required',
            ]);
            
            $credentials = [
                'name' => $request->input('name'),
                'password' => $request->input('password'),
            ];
            try {
                if (Auth::attempt($credentials)) {
                    $userName = Auth::user()->name; // Mengambil nama pengguna yang sedang masuk
                    return redirect()->route('home')
                    ->with('success', 'Selamat datang kembali ' . $userName);
                }
            } catch (\Exception $e) {
                // Tangani pengecualian dan kirimkan pesan kesalahan ke tampilan
                return redirect()->back()
                ->with('error', 'password' . $e->getMessage());
            }
            
            
            return redirect()->back()->with('error', 'Username atau Password salah!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal melakukan login. Error: ' . $e->getMessage());
        }
    }

    

    public function doRegis(Request $request) {
        try {

            $obj = new User();
            $obj->name = $request->name;
            $obj->email = $request->email;
            $obj->password = Hash::make($request->input('password'));
            $obj->img = null;
            
            $obj->save();
                        return redirect()->route('home')->with('success', 'registrasi berhasil');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal melakukan regis. Error: ' . $e->getMessage());
        }
    }

    public function doLogout()
    {
        $userName = Auth::user()->name;
        Auth::logout(); // menghapus session yang aktif
        return redirect()->route('login')->with('success', 'Sampai bertemu kembali ' . $userName);
    }
}
