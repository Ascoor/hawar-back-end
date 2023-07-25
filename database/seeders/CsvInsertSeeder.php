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
    private function getMemberIdAndRegNumByName($name)
    {
        $member = Member::where('Name', $name)->first();
    
        if ($member) {
            $memberId = $member->id;
            $regNum = $member->RegNum;
            return ['MemberId' => $memberId, 'RegNum' => $regNum];
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

                $existingMemberFee = $this->getMemberFeeByFeeId($data[7]);

                if ($existingMemberFee) {
                    $output->writeln("الصف رقم $counter برقم الرسم $data[7] موجود بالفعل. تم تخطيه.");
                    continue;
                }

                $rowData = [
                    'Name' => $data[1],
                    'FeeId' => $data[7],
                    'RegNum' => $data[0],
                    'FeeYear' => $data[8],
                    'FeeAmount' => $data[9],
                    'FeeDate' => $data[10],
                    'FeeRecieptNumber' => $data[11],
                    'FeeStatus' => $data[12],
                ];
                
                // Get the 'MemberId' and 'RegNum' from the result of getMemberIdAndRegNumByName
                $memberData = $this->getMemberIdAndRegNumByName($data[1]);
                if ($memberData) {
                    $rowData['MemberId'] = $memberData['MemberId'];
                    $rowData['RegNum'] = $memberData['RegNum'];
                }
                $memberFee = new MemberFee($rowData);
                $memberFee->timestamps = false;
                $memberFee->save();

                $rowsInserted++; // Increment the rows inserted counter

                if ($counter % $batchSize === 0) {
                    DB::commit();
                    sleep(7); // Sleep for 7 seconds
                    DB::beginTransaction();
                    $this->storeLastProcessedRow($counter);
                }

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
