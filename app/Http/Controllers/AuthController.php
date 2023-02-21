<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View | Factory | Application
    {
        try {
            User::query()->exists();
        } catch (Throwable $e) {
            Artisan::call('migrate:fresh --seed');
        }

        return view('auth.login');
    }


    /**
     * @param Request $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function customLogin(Request $request)
    {
        $request->validate([
                               'email'    => 'required',
                               'password' => 'required',
                           ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            User::query()->where('email', $request->get('email'))->touch('updated_at');

            return redirect()->intended()
                ->withErrors('Signed in');
        }

        return redirect("login")->withErrors('Login details are not valid');
    }

    /**
     * @param array $data
     *
     * @return User|Model
     */
    public static function create(array $data)
    {
        return User::create(
            [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]
        );
    }


    /**
     * @param Request $request
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();

        return redirect('login');
    }
}