<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voo extends Model
{
    use HasFactory;

    protected $table = 'voo';
    protected $fillable = ['numero_voo', 'data', 'origem', 'destino'];

    public function reserva()
    {
        return $this->hasMany(Reserva::class);
    }
}
