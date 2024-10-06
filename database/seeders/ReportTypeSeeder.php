<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reportTypes = [
            [
                'ReportTypeOrderPriority' => 1,
                'ReportTypeName' => 'Bache',
                'ReportTypePinReported' => null,
                'ReportTypePinInProcess' => null,
                'ReportTypePinFinished' => null,
                'ReportTypeImage' => null,
                'ReportTypeIsActive' => true,
                'ReportTypeRegistrationDate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ReportTypeOrderPriority' => 2,
                'ReportTypeName' => 'Fuga de agua',
                'ReportTypePinReported' => null,
                'ReportTypePinInProcess' => null,
                'ReportTypePinFinished' => null,
                'ReportTypeImage' => null,
                'ReportTypeIsActive' => true,
                'ReportTypeRegistrationDate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ReportTypeOrderPriority' => 3,
                'ReportTypeName' => 'Corte de luz',
                'ReportTypePinReported' => null,
                'ReportTypePinInProcess' => null,
                'ReportTypePinFinished' => null,
                'ReportTypeImage' => null,
                'ReportTypeIsActive' => true,
                'ReportTypeRegistrationDate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ReportTypeOrderPriority' => 4,
                'ReportTypeName' => 'Basura acumulada',
                'ReportTypePinReported' => null,
                'ReportTypePinInProcess' => null,
                'ReportTypePinFinished' => null,
                'ReportTypeImage' => null,
                'ReportTypeIsActive' => true,
                'ReportTypeRegistrationDate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ReportTypeOrderPriority' => 5,
                'ReportTypeName' => 'Señalización dañada',
                'ReportTypePinReported' => null,
                'ReportTypePinInProcess' => null,
                'ReportTypePinFinished' => null,
                'ReportTypeImage' => null,
                'ReportTypeIsActive' => true,
                'ReportTypeRegistrationDate' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('report_types')->insert($reportTypes);
    }
}
