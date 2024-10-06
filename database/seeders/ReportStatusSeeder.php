<?php

namespace Database\Seeders;

use App\Models\ReportStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportStatus::create(['report_status_id' => 1, 'report_status_name' => 'Reportado']);
        ReportStatus::create(['report_status_id' => 2, 'report_status_name' => 'En proceso']);
        ReportStatus::create(['report_status_id' => 3, 'report_status_name' => 'Terminado']);
        ReportStatus::create(['report_status_id' => 4, 'report_status_name' => 'Cancelado']);
    }
}
