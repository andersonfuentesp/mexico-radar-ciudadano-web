<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estado';
    protected $primaryKey = 'EstadoId';
    public $incrementing = false;
    public $timestamps = false;

    protected $guarded = [];

    public function estadopols()
    {
        return $this->hasMany(EstadoPol::class, 'EstadoPolId', 'EstadoId');
    }

    public function estadoubis()
    {
        return $this->hasMany(EstadoUbi::class, 'EstadoId', 'EstadoId');
    }

    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'EstadoId', 'EstadoId');
    }
}
