<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];
    

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // Relacion uno a muchos con Tickets

    public function tickets()
    {
      return $this->hasMany('App\Models\Ticket');
    }

     // Relacion uno a muchos:Inversa con area

    public function areas()
    {
        return $this->belongsToMany('App\Models\Area');
    }

    public function mensajes()
    {
      return $this->hasMany('App\Models\Mensaje');
    }

  
    // METODOS PARA EL MENU DE SESION DEL USUARIO 
    // VINCULADOS A CONFIG/ADMINLTE.PHP

    public function adminlte_image(){
        // retornamos la imagen de perfil del usuario logueado
        return url ($this->profile_photo_url);
    }
 

  
    public function adminlte_desc(){
        // retornamos el rol del usuario logueado
       
        return Auth::user()->roles()->pluck('name')->implode(', ');
    }


    public function adminlte_profile_url(){
        // ruta al perfil del usuario logueado
        return url('user/profile');
    }

}
