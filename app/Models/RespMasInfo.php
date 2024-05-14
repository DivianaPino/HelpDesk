<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespMasInfo extends Model
{
    use HasFactory; 

    public function ticket()
    {
      return $this->belongsTo('App\Models\Ticket');
    } 
 
    public function masInfo()
    {
        return $this->hasOne('App\Models\MasInformacion', 'id', 'masInfo_id');
    }




}
