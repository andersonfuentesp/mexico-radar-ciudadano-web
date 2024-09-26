<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Define relationships
    protected $table = 'reports';
    protected $primaryKey = 'report_id';
    public $incrementing = false;
    protected $keyType = 'char';

    protected $guarded = [];

    // Define relationships
    public function state()
    {
        return $this->belongsTo(Estado::class, 'state_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipio::class, 'municipality_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood_id');
    }

    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }

    public function reportStatus()
    {
        return $this->belongsTo(ReportStatus::class, 'report_status_id');
    }
}
