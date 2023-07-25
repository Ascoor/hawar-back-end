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
        $csvFile = fopen(public_path('data/member_fees.csv'), 'r');
        DB::beginTransaction();

        $output = new ConsoleOutput();
        $chunkSize = 1000;
        $batchSize = 50000;
        $lastProcessedRow = $this->getLastProcessedRow() + 1;
        $counter = 0;

        try {
            while (($data = fgetcsv($csvFile)) !== false) {
                $counter++;

                if ($counter < $lastProcessedRow) {
                    continue;
                }

                $output->writeln("جاري معالجة الصف: $counter");

                $existingMemberFee = $this->getMemberFeeByFeeId($data[10]);

                if ($existingMemberFee) {
                    $output->writeln("الصف رقم $counter برقم الرسم $data[10] موجود بالفعل. تم تخطيه.");
                    continue;
                }

                $rowData = [
                    'Member_ID' => $this->getMemberIdByRegNum($data[2]),
                    'Name' => $data[1],
                    'FeeId' => $data[7],
                    'RegNum' => $data[2],
                    'FeeYear' => $data[8],
                    'FeeAmount' => $data[9],
                    'FeeDate' => $data[10],
                    'FeeRecieptNumber' => $data[11],
                    'FeeStatus' => $data[12],
                ];

                $memberFee = new MemberFee($rowData);
                $memberFee->timestamps = false;
                $memberFee->save();

                if ($counter % $batchSize === 0) {
                    DB::commit();
                    sleep(5);
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

        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($csvFile);
            dd($e->getMessage());
        }
    }
}
