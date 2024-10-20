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
                'ReportTypeName' => 'BACHE',
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
                'ReportTypeName' => 'FUGA DE AGUA',
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
                'ReportTypeName' => 'VEHÍCULO ABANDONADO',
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
                'ReportTypeName' => 'BASURA ACUMULADO',
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
                'ReportTypeName' => 'ALUMBRADO PÚBLICO',
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
                'ReportTypeOrderPriority' => 6,
                'ReportTypeName' => 'ALCANTARILLA',
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
                'ReportTypeOrderPriority' => 7,
                'ReportTypeName' => 'EXTORSIONES',
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
                'ReportTypeOrderPriority' => 8,
                'ReportTypeName' => 'MUJER VIOLENTADA',
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
                'ReportTypeOrderPriority' => 9,
                'ReportTypeName' => 'PERSONA DESAPARECIDA',
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
                'ReportTypeOrderPriority' => 10,
                'ReportTypeName' => 'FALTA DE AGUA',
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
                'ReportTypeOrderPriority' => 11,
                'ReportTypeName' => 'MALTRATO ANIMAL',
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
                'ReportTypeOrderPriority' => 12,
                'ReportTypeName' => 'PERROS AGRESIVOS',
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
                'ReportTypeOrderPriority' => 13,
                'ReportTypeName' => 'PIPAS DE AGUA',
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
