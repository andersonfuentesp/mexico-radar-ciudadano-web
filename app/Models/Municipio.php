<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipio';
    protected $primaryKey = ['EstadoId', 'MunicipioId'];
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'EstadoId', 'EstadoId');
    }

    public function municipiopols()
    {
        return $this->hasMany(MunicipioPol::class, ['EstadoPolId', 'MunicipioPolId'], ['EstadoId', 'MunicipioId']);
    }
}