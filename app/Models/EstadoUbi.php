<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadoUbi extends Model
{
    protected $table = 'estadoubi';
    protected $primaryKey = ['EstadoUbiId', 'EstadoUbiNumero'];
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'EstadoUbiId', 'EstadoId');
    }
}
