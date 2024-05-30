<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    public function respuesta()
    {
      return $this->belongsTo('App\Models\Respuesta');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

}
