<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['numero_reserva', 'valor', 'voo_id', 'passageiro_id'];

    public function voo()
    {
        return $this->belongsTo(Voo::class);
    }

    public function passageiro()
    {
        return $this->belongsTo(Passageiro::class);
    }
}
