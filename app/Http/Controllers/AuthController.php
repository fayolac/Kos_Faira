<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Penyewa;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    //Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) 
        {
            $request->session()->regenerate();
            return redirect('/admin/keuangan');
        }

        if (Auth::guard('penyewa')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) 
        {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()
               ->with('error', 'Email atau password salah.')
               ->withInput($request->only('email'));
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:penyewa,email',
            'password' => 'required|min:8',
            'no_telp'  => 'required|numeric|digits_between:10,13',
            'pekerjaan'=> 'nullable|string|max:100',
            'agama'    => 'nullable|string|max:30',
            'foto_ktp' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $ktpPath = $request->file('foto_ktp')
                           ->store('foto_ktp', 'public');
        Penyewa::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'no_telp'  => $request->no_telp,
            'pekerjaan'=> $request->pekerjaan,
            'agama'    => $request->agama,
            'foto_ktp' => $ktpPath,
            'status'   => 'Nonaktif',
        ]);

        return redirect('/login')
               ->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    //Logout
    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('penyewa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
