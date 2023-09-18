<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();
        // dd($this->username);

        //get user in database
        $user = User::where('username', $this->username)->first();
        // check if user is deleted or if password does not match 
        if(!empty($user) || $user != null){
            if($user->deleted ==='1' || !Hash::check($this->password, $user->password)){

            
                throw ValidationException::withMessages([
                    'username' => __('auth.failed'),
                ]);
            }else{
                if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
                    RateLimiter::hit($this->throttleKey());
        
                    
        
                    throw ValidationException::withMessages([
                        'username' => __('auth.failed'),
                    ]);
                }
        
                RateLimiter::clear($this->throttleKey());
            }
        }else{
            throw ValidationException::withMessages([
                'username' => __('auth.failed'),
            ]);
        }
      

       
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('username')).'|'.$this->ip();
    }
}
