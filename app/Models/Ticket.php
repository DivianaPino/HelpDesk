<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\TicketUpdate;

class Ticket extends Model
{
    use HasFactory;
    use Notifiable;

    public function user()
    {
      return $this->belongsTo('App\Models\User');
    } 

    public function area()
    {
      return $this->belongsTo('App\Models\Area');
    } 

    public function servicio()
    {
      return $this->belongsTo('App\Models\Servicio');
    } 

    public function estado()
    {
      return $this->belongsTo('App\Models\Estado');
    } 

    public function prioridad()
    {
      return $this->belongsTo('App\Models\Prioridad');
    } 

    public function mensajes() 
    {
        return $this->hasMany('App\Models\Mensaje');
    }

    // public function masInformacions()
    // {
    //   return $this->hasMany('App\Models\MasInformacion');
    // }

    // public function respMasInfo()
    // {
    //     return $this->hasMany('App\Models\RespMasInfo');
    // }

    public function calificaciones() 
    {
        return $this->hasMany('App\Models\Calificacion');
    }

    public function ultimaCalificacion()
    {
      return $this->hasOne('App\Models\Calificacion')->latest();
    }

   



    public function ticketHistorials()
    {
        return $this->hasMany('App\Models\TicketHistorial'); 
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comentario');
    }

    public function esCerradoPorTiempo()
    {
        // Verifica si el ticket fue creado hace más de una semana
        $tiempoDesdeCreacion = Carbon::parse($this->created_at)->addWeeks(1);
        if ($tiempoDesdeCreacion < now()) {
            // Verifica si no tiene comentarios asociados
            if (!$this->comments()->exists()) {
                return true;
            }
        }

        return false;
    }

    // public function notifications()
    // {
    //     return $this->hasMany(Notification::class);
    // }

}
