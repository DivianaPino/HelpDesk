<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketHistorial extends Model
{
    use HasFactory;

    public $timestamps = false; // Desactivar los timestamps por defecto

    const CREATED_AT = null; // Desactivar el uso de created_at
    const UPDATED_AT = 'updated_at'; // Especificar que solo se utilice updated_at


    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket');
    }

    public function estado()
    {
        return $this->belongsTo('App\Models\Estado');
    }


}
