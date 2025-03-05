<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'email',
        'password',
        'phone_number',
        'first_name',
        'last_name',
        'birth_day',
        'address',
        'type_payment',
        'verify_email',
        'verify_number',
        'coin',
        'role',
        'dark_mode',
        'remember_token',
        'device_token',
        'start_price',
        'end_price',
        'sort_price',
        'sort_favourite',
        'sort_sale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAgeAttribute()
    {
        if ($this->birth_day) {
            return Carbon::parse($this->birth_day)->age;
        }
        
        return null; 
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function messagesSent()
    {
        return $this->hasMany(Message::class, 'from_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'to_id');
    }


    public function getAddress(){
        return $this->hasMany(Address::class);
    }

    public function getLog(){
        return $this->hasMany(user_log::class);
    }
}
