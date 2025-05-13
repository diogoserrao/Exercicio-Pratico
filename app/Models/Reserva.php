<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reserva';
    protected $fillable = ['numero_reserva', 'preco', 'voo_id', 'passageiro_id'];

    public function voo()
    {
        return $this->belongsTo(Voo::class);
    }

    public function passageiro()
    {
        return $this->belongsTo(Passageiro::class);
    }
}
