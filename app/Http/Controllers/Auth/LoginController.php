<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }




    // public function login(Request $request)
    // {
    //     $input = $request->all();

    //     $this->validate($request, [
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);
    //     if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    //         $fieldType =  'email';
    //         if (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])) &&  auth()->user()->type === 1) {
    //             return redirect()->to('admin/dashboard');
    //         } elseif (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])) &&  auth()->user()->type === 2) {
    //             return redirect()->to('admin/testing');
    //         } elseif (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])) &&  auth()->user()->type === 3) {
    //             return redirect()->to(route('admin.investment_partner'));
    //         } elseif (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])) &&  auth()->user()->type === 4) {
    //             return redirect()->to('admin/sales-management');
    //         } elseif (auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password'])) &&  auth()->user()->type === 5) {
    //             return redirect()->to('admin/data-entry');
    //         } else {
    //             return redirect()->route('login')
    //                 ->with('error', 'Email-Address And Password Are Wrong.');
    //         }
    //     } else {
    //         return redirect()->route('login')
    //             ->with('error', 'Email-Address And Password Are Wrong.');
    //     }
    // }
    public function login(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    // Attempt to authenticate the user
    if (auth()->attempt($credentials)) {
        // User is authenticated
        $user = auth()->user();

        // Redirect based on user type
        switch ($user->type) {
            case 1:
                return redirect()->to('admin/dashboard');
            case 2:
                return redirect()->to('admin/testing');
            case 3:
                return redirect()->route('admin.investment_partner');
            case 4:
                return redirect()->to('admin/sales-management');
            case 5:
                return redirect()->to('admin/data-entry');
            default:
                return redirect()->route('login')->with('error', 'User type not recognized.');
        }
    }

    // Authentication failed
    return redirect()->route('login')->with('error', 'Email-Address And Password Are Wrong.');
}

}
