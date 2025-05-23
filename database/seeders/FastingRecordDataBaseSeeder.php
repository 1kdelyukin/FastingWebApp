<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FastingRecord;
class FastingRecordDataBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

                //seed the fasting records for everyday of the week for the last 3 months.
                $date = date('Y-m-d', strtotime('-3 months'));
                $endDate = date('Y-m-d');
                while (strtotime($date) <= strtotime($endDate)) {
                    try {
                        FastingRecord::factory()->create([
                            'record_date' => $date,
                        ]);
                    } catch (\Exception $e) {
                        $this->command->error($e->getMessage());
                    }
                    $date = date('Y-m-d', strtotime($date . ' + 1 days'));
                }
                
    }
}
