<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasInformacion extends Model
{
    use HasFactory;

    public function ticket()
    {
      return $this->belongsTo('App\Models\Ticket');
    } 

    public function ticketHistorials()
    {
        return $this->hasMany('App\Models\TicketHistorial'); 
    }

    public function respMasInfo()
    {
        return $this->belongsTo('App\Models\RespMasinfo');
    }

}
