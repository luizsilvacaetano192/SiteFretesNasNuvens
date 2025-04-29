<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function index(Request $request)
    {
        
        return view('login');
    }

    public function handleLogin(Request $request)
    {

        if ($response = $this->checkTooManyFailedAttempts($request)) {
            return $response;
        }

   

        $credentials = $request->validate([
            'cnpj' => 'required|string',
            'password' => 'required|string',
        ]);
        
        $credentials['cnpj'] = preg_replace('/[^0-9]/', '', $credentials['cnpj']);

        
        
        
        if ($this->attemptLogin($credentials, $request->boolean('remember'))) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(array $credentials, $remember = false)
    {
        $user = User::where('cnpj', $credentials['cnpj'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        Auth::login($user, $remember);
        RateLimiter::clear($this->throttleKey(request()));
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        
        $token = $request->user()->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'redirect_to' => $request->user()->isAdmin() ? '/freights' : '/cliente',
            'access_token' => $token
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'cnpj' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            $request->user()->currentAccessToken()?->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    protected function checkTooManyFailedAttempts(Request $request)
    {
        $key = $this->throttleKey($request);
    
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
    
            return response()->json([
                'message' => 'Muitas tentativas. Tente novamente em ' . $seconds . ' segundos.',
                'retry_after' => $seconds
            ], 429);
        }
    
        return null;
    }
    
    
    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit(
            $this->throttleKey($request),
            $seconds = 60
        );
    }
    
   

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('cnpj')).'|'.$request->ip();
    }
}