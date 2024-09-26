<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    use HasFactory;

    protected $table = 'neighborhoods';
    protected $primaryKey = ['state_id', 'municipality_id', 'neighborhood_id'];
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $guarded = [];

    public function municipality()
    {
        return $this->belongsTo(Municipio::class, ['state_id', 'municipality_id']);
    }
}
