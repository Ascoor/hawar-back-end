<?php

namespace Database\Seeders;

use App\Imports\MemberFeesImport;
use App\Models\Member;
use App\Models\MemberFee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertSeeder extends Seeder
{
    private function getMemberFeeByFeeId($feeId)
    {
        return MemberFee::where('FeeId', $feeId)->first();
    }

    private function getLastProcessedRowTime()
    {
        return Carbon::parse(DB::table('last_processed_row')->value('updated_at'));
    }

    private function storeLastProcessedRow($rowNumber)
    {
        DB::table('last_processed_row')->updateOrInsert(['id' => 1], ['row_number' => $rowNumber, 'updated_at' => Carbon::now()]);
    }

    private function getMemberIdByRegNum($regNum)
    {
        $member = Member::where('RegNum', $regNum)->first();
        return $member ? $member->id : null;
    }

    private function getLastProcessedRow()
    {
        return DB::table('last_processed_row')->value('row_number') ?? 0;
    }

    public function run()
    {
        DB::beginTransaction();

        $output = new ConsoleOutput();
        $chunkSize = 1000;
        $batchSize = 50000;

        try {
            $import = new MemberFeesImport();
            $filePath = public_path('data/member_fees.csv');

            // Get the last processed row from the database
            $lastProcessedRow = $import->getLastProcessedRow();
            $import->storeLastProcessedRow($lastProcessedRow);

            // Import the data from the CSV file
            Excel::import($import, $filePath);

            // Get the current row number and total row count
            $currentRow = $import->getCurrentRow();
            $rowCount = $import->getRowCount();

            while ($currentRow < $rowCount) {
                $rowData = $import->getRowData();

                // Add your logic for processing each row here

                // Example:
                $existingMemberFee = $this->getMemberFeeByFeeId($rowData[10]);

                if ($existingMemberFee) {
                    continue;
                }

                $memberId = $this->getMemberIdByRegNum($rowData[2]);

                $data = [
                    'Member_ID' => $memberId,
                    'Name' => $rowData[1],
                    'FeeId' => $rowData[7],
                    'RegNum' => $rowData[2],
                    'FeeYear' => $rowData[8],
                    'FeeAmount' => $rowData[9],
                    'FeeDate' => $rowData[10],
                    'FeeRecieptNumber' => $rowData[11],
                    'FeeStatus' => $rowData[12],
                ];

                // Save the data to the database
                $memberFee = new MemberFee($data);
                $memberFee->timestamps = false;
                $memberFee->save();

                if ($currentRow % $batchSize === 0) {
                    DB::commit();
                    sleep(5);
                    DB::beginTransaction();
                    $import->storeLastProcessedRow($currentRow);
                }

                if ($currentRow % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                    $import->storeLastProcessedRow($currentRow);
                }

                $import->nextRow();
                $currentRow++;
            }

            DB::commit();
            $import->storeLastProcessedRow($currentRow);

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
