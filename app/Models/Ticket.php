<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TicketUpdate;

class Ticket extends Model
{
    use HasFactory;

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    } 

    public function clasificacion()
    {
      return $this->belongsTo('App\Models\Clasificacion');
    } 

    public function estado()
    {
      return $this->belongsTo('App\Models\Estado');
    } 

    public function prioridad()
    {
      return $this->belongsTo('App\Models\Prioridad');
    } 

    public function respuestas() 
    {
        return $this->hasMany('App\Models\Respuesta');
    }

    public function masInformacions()
    {
      return $this->hasMany('App\Models\MasInformacion');
    }

    public function respMasInfo()
    {
        return $this->hasMany('App\Models\RespMasinfo');
    }

    public function ultimaRespuesta()
    {
      return $this->hasOne('App\Models\Respuesta')->latest();
    }

    public function ticketHistorials()
    {
        return $this->hasMany('App\Models\TicketHistorial'); 
    }

}
