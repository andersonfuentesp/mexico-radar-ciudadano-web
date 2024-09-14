<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoPol extends Model
{
    protected $table = 'estadopol';
    protected $primaryKey = ['EstadoPolId', 'EstadoPolNumero'];
    public $incrementing = false; // Clave primaria compuesta
    public $timestamps = false;

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'EstadoPolId', 'EstadoId');
    }
}