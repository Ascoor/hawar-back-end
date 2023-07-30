<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberFee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertSeeder extends Seeder

{
    private function getMemberIdByName($name)
{
    $member = Member::where('ame', $name)->first();

    if ($member) {
        $member_id = $member->id; // Use the 'id' column as 'member_id' is now the primary key in the members table
        return $member_id;
    }

    return null; // Return null or handle the case when member data is not found.
}

  public function run()
{
    $csvFile = fopen(public_path('data/member_fees.csv'), 'r');
    DB::beginTransaction();

    $output = new ConsoleOutput();
    $batchSize = 1000;
    $rows = [];

    while (($data = fgetcsv($csvFile)) !== false) {
        $rows[] = [
            'member_id' => $this->getMemberIdByName($data[1]),
            'name' => $data[1],
            'fee_id' => $data[7],
            'fee_year' => $data[8],
            'fee_amount' => $data[9],
            'fee_date' => $data[10],
            'fee_recieptNumber' => $data[11],
            'fee_status' => $data[12],
        ];

        if (count($rows) >= $batchSize) {
            DB::table('member_fees')->insert($rows);
            $rows = [];
        }
    }

    // Insert remaining rows
    if (!empty($rows)) {
        DB::table('member_fees')->insert($rows);
    }

    fclose($csvFile);

    DB::commit();

    $output->writeln("Rows imported successfully!");
}


}
