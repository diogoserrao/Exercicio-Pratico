<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voo extends Model
{
    use HasFactory;

     protected $fillable = ['numero_voo', 'data', 'origem', 'destino'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
