<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractedMunicipality extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relación uno a muchos con MunicipalityService
    public function services()
    {
        return $this->hasMany(MunicipalityService::class, 'municipality_id', 'id');
    }
}
