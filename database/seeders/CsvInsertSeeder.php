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
    $member = Member::where('name', $name)->first();

    if ($member) {
        $memberId = $member->id; // Use the 'id' column as 'member_id' is now the primary key in the members table
        return $memberId;
    }

    return null; // Return null or handle the case when member data is not found.
}


    private function getMemberFeeByFeeId($feeId)
  {
      return MemberFee::where('FeeId', $feeId)->first();
  }

  private function getLastProcessedRow()
  {
      return DB::table('last_processed_row')->value('row_number') ?? 0;
  }
  private function storeLastProcessedRow($rowNumber)
  {
      DB::table('last_processed_row')->updateOrInsert(['id' => 1], ['row_number' => $rowNumber, 'updated_at' => Carbon::now()]);
  }
    public function run()
    {
        $csvFile = fopen(public_path('data/member_fees.csv'), 'r');
        DB::beginTransaction();

        $output = new ConsoleOutput();
        $chunkSize = 1000;
        $batchSize = 50000;
        $lastProcessedRow = $this->getLastProcessedRow() + 1;
        $counter = 0;
        $rowsInserted = 0; // Initialize the counter for rows inserted

        try {

while (($data = fgetcsv($csvFile)) !== false) {
    $counter++;

    if ($counter < $lastProcessedRow) {
        continue;
    }

    $output->writeln("File under check: $counter");

    // ... (existing code)

    $rowData = [
        'member_id' => $this->getMemberIdByName($data[1]),
        'name' => $data[1],
        'fee_id' => $data[7],
        'fee_year' => $data[8],
        'fee_amount' => $data[9],
        'fee_date' => $data[10],
        'fee_recieptNumber' => $data[11],
        'fee_status' => $data[12],
    ];


                 // Get the 'MemberId' using the updated function getMemberIdByName
    $memberId = $this->getMemberIdByName($data[1]);
    if ($memberId) {
        $rowData['MemberId'] = $memberId;
    }

    $memberFee = new MemberFee($rowData);
    $memberFee->timestamps = false;
    $memberFee->save();


                if ($counter % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                    $this->storeLastProcessedRow($counter);
                }
            }

            DB::commit();
            fclose($csvFile);
            $this->storeLastProcessedRow($counter);

            $output->writeln("تم إدراج $rowsInserted صف في قاعدة البيانات.");

        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($csvFile);
            dd($e->getMessage());
        }
    }




}
