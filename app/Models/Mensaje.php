<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    public function ticket()
    {
      return $this->belongsTo('App\Models\Ticket');
    } 

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    } 



    public function tcketHistorials()
    {
      return $this->hasMany('App\Models\TicketHistorial');
    }
}
