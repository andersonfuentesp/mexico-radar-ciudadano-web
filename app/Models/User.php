<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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

    public function adminlte_image()
    {
        //return "https://picsum.photos/300/300";
        if (!empty(auth()->user()->image)) {
            return asset(auth()->user()->image);
        } else {
            return "https://picsum.photos/300/300";
        }
    }

    public function adminlte_desc()
    {
        $rolesName = User::findOrFail(auth()->user()->id)->getRoleNames()->toArray();
        return implode(" | ", $rolesName);
    }

    public function adminlte_profile_url()
    {
        return "app/mi-perfil";
    }
}
