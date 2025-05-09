<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passageiro extends Model
{
    use HasFactory;

     protected $fillable = ['nome', 'identificacao', 'nif', 'telefone', 'email'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }
}
