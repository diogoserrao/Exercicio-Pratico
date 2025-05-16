<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Cidade;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voo extends Model
{
    use HasFactory;

    protected $table = 'voo';
    protected $fillable = ['numero_voo', 'data', 'origem_id', 'destino_id'];

    public function reserva()
    {
        return $this->hasMany(Reserva::class);
    }

    public function origem()
{
    return $this->belongsTo(Cidade::class, 'origem_id');
}
public function destino()
{
    return $this->belongsTo(Cidade::class, 'destino_id');
}
}
