<?php

namespace Database\Seeders;

use App\Models\ContractedMunicipality;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractedMunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractedMunicipality::create([
            'state_id' => 15,
            'municipality_id' => 33,
            'name' => 'Municipio Ecatepec',
            'description' => 'Municipio Ecatepec',
            'url' => 'http://127.0.0.1:8001',
            'token' => 'dfjb7821BJBSDNM2FfNFH2984',
            'contract_date' => now()->format('Y-m-d'),
            'contract_number' => '1',
            'contact_responsible' => 'Juan Pérez',
            'contact_position' => 'Alcalde',
            'contact_email' => 'contacto@ecatepec.gob.mx',
            'contact_phone1' => '5551234567',
            'contact_phone2' => '5557654321',
            'status' => true,
        ]);
    }
}
