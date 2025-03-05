<?php

namespace App\Http\Controllers\Auth;

use App\Client\config;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(config $config)
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'integer', 'in:1,2'], 
            'password' => ['required', 'string', 'min:8'],
            'gender' => ['required', 'in:male,female'], 
            'date' => ['required', 'integer', 'min:1', 'max:31'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:1950', 'max:2024'],
        ];

        if ($data['type'] == 1) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            $rules['phone_number'] = ['nullable'];
        } else {
            $rules['phone_number'] = ['required', 'string', 'max:15', 'unique:users'];
            $rules['email'] = ['nullable'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Check if a user with the provided email already exists
        if (isset($data['email']) && User::where('email', $data['email'])->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['An account with this email already exists.'],
            ]);
        }

        // Check if a user with the provided phone number already exists
        if (isset($data['phone_number']) && User::where('phone_number', $this->convertToInternationalFormat($data['phone_number']))->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'phone_number' => ['An account with this phone number already exists.'],
            ]);
        }

        // Proceed with creating the user
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?? null, 
            'phone_number' => isset($data['phone_number']) ? $this->convertToInternationalFormat($data['phone_number']) : null,
            'password' => Hash::make($data['password']),
            'gender' => $data['gender'],
            'birth_day' => $data['year'] . '-' . str_pad($data['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['date'], 2, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Convert phone number to international format.
     *
     * @param string $phone
     * @return string
     */
    protected function convertToInternationalFormat(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // If the phone number starts with 0, replace it with +84
        if (substr($phone, 0, 1) === '0') {
            $phone = '+84' . substr($phone, 1);
        }

        return $phone;
    }
}
