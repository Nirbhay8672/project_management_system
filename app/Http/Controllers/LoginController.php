<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('auth/Login');
    }

    public function postLogin(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {

            return response()->json([
                'url' => session('url.intended'),
                'message' => 'Login successfully.',
                'is_success' => true
            ], 200);
        }

        return response()->json([
            'message' => 'Username or password incorrect.',
            'is_success' => false
        ], 200);
    }

    public function logOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('/login');
    }
}