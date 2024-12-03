<?php

namespace App\Models;

use App\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User implements MustVerifyEmail
{
    use HasFactory ,
         Notifiable,
        HasRoles;

    protected $fillable=[
        'name','email','password','phone_number',
        'super_admin', 'status'
    ];
}
