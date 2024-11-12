<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public function users()
    {
      return $this->belongsToMany('App\Models\User');
    } 

    public function areas() 
    {
        return $this->hasMany('App\Models\Area');
    }

    public function servicios() 
    {
        return $this->hasMany('App\Models\Servicio');
    }

   

}
