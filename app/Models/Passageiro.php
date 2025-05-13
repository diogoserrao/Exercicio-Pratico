<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passageiro extends Model
{
    use HasFactory;

    protected $table = 'passageiro';
    protected $fillable = ['nome', 'identificacao', 'nif', 'telefone', 'email'];

    public function reserva()
    {
        return $this->hasMany(Reserva::class);
    }
}
