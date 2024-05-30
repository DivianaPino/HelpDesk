<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;

    public function ticket()
    {
      return $this->belongsTo('App\Models\Ticket');
    } 

    public function comentario()
    {
      return $this->hasOne('App\Models\Comentario');
    }

    public function ticketHistorials()
    {
      return $this->hasMany('App\Models\TicketHistorial');
    }
}

