<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Irazasyed\LaravelIdenticon\Identicon;


class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Les tableaux des providers autorisés
    protected $providers = [ "google", "github", "facebook","linkedin"];

    public function redirect (Request $request) {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {
            return Socialite::driver($provider)->redirect();
        }
        abort(404);
    }

    public function callback (Request $request) {

        $provider = $request->provider;

        if (in_array($provider, $this->providers)) {
            
            $user = Socialite::driver($provider)->user();
            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);
            
            return redirect(route('home'));
        }
        abort(404);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'username' => $user->getNickname() ?? $user->getName(),
            'email'    => $user->email,
            'avatar' => $user->getAvatar(),
            'provider' => $provider,
            'provider_id' => $user->id
        ]);
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'username' => 'bail|required|max:25|unique:users',
            'email' => 'bail|max:25|required|unique:users',
            'password' => 'bail|required|confirmed'
        ]);

        // create avatar with identicon
        $identicon = new Identicon();
        $avatar =  $identicon->getImageData($validatedData['email'],250);
        $url = 'avatars/'. $validatedData['username'] .'_'. time();
        Storage::disk('public')->put($url, $avatar);

        $validatedData['avatar'] = $url;
        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        if ($user) {
            Auth::attempt($request->only('email','password'));
            $request->session()->regenerate();

            return redirect('/');
        }
        else {
            return redirect()->back()->withInput()->withError('An error occured, please retry');
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|max:25',
            'password' => 'required'
        ]);

        $loginData = $request->all();

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        if (Auth::attempt([$fieldType => $loginData['email'], 'password' => $loginData['password']])) {
            $request->session()->regenerate();

            return redirect('/');
        }

        return redirect()->back()->withInput($request->only('email'))->withError('user or password not correct');
    }

    public function getUser()
    {
        return auth()->user();
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login')->withMsg('logged out successfully');
    }

    // TODO: login and signin via social network

}
