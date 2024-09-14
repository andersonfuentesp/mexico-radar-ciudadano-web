<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MunicipioPol extends Model
{
    protected $table = 'municipiopol';
    protected $primaryKey = ['EstadoPolId', 'MunicipioPolId', 'MunicipioPolNumero'];
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, ['EstadoPolId', 'MunicipioPolId'], ['EstadoId', 'MunicipioId']);
    }
}