<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MunicipalityService extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relación inversa con ContractedMunicipality
    public function contractedMunicipality()
    {
        return $this->belongsTo(ContractedMunicipality::class, 'municipality_id', 'id');
    }
}
