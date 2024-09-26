<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportStatus extends Model
{
    use HasFactory;

    protected $table = 'report_statuses';
    protected $primaryKey = 'report_status_id';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $guarded = [];
}
