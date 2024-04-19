<?php

namespace App\Http\Controllers;

use App\Models\ProfileImage;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'username' => $googleUser->name,
                'first_name' => $googleUser->user['given_name'],
                'last_name' => $googleUser->user['family_name'],
                'email' => $googleUser->email,
                'email_verified_at' => Carbon::now(),
                'password' => bcrypt($googleUser->user['given_name'])
            ]);

            $client = new Client();
            $response = $client->get($googleUser->avatar);

            $path = 'user_' . time() . (string) random_int(0, 5) . '.jpg';
            Storage::disk('public')->put('user/' . $user->id . '/' . $path, $response->getBody());

            $profile_image = new ProfileImage;

            $profile_image->fill([
                'user_id' => $user->id,
                'file_name' => $path,
                'file_path' => 'user/' . $user->id . '/' . $path,
                'file_extension' => '.jpg',
                'file_size' => 2000,
            ])->save();

            $permissions = Permission::all();

            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
            }
        }

        Auth::login($user, true);

        return redirect()->route('dashboard');
    }

    public function logOut()
    {
        Session::flush();
        Auth::logout();

        return redirect('/login');
    }
}