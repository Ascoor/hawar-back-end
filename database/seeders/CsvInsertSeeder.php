<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberFee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertSeeder extends Seeder
{
    /**
     * تنفيذ بذر البيانات في قاعدة البيانات.
     *
     * @return void
     */
    public function run()
    {
        // فتح ملف CSV للقراءة
        $csvFile = fopen(public_path('data/member_fees.csv'), 'r');

        // بدء التحويل في قاعدة البيانات
        DB::beginTransaction();

        // تهيئة Symfony ConsoleOutput
        $output = new ConsoleOutput();

        try {
            // تحديد عدد الصفوف لمعالجتها في كل دفعة
            $chunkSize = 1000;
            $startRow = 1; // The row number to start from
            $counter = 0;

            while (($data = fgetcsv($csvFile)) !== false) {
                // Increment the counter and check if it reaches the chunk size
                $counter++;
                if ($counter < $startRow) {
                    continue; // Skip rows until the starting row number is reached
                }

                // Display the current row being processed
                $output->writeln("جاري معالجة الصف: $counter");

                // Map CSV data to table columns
                $rowData = [
                    'Member_ID' => $this->getMemberIdByRegNum($data[2]),
                    'Name' => $data[1],
                    'RegNum' => $data[2],
                    'FeeYear' => $data[8],
                    'FeeAmount' => $data[9],
                    'FeeDate' => $data[10],
                    'FeeRecieptNumber' => $data[11],
                    'FeeStatus' => $data[12],
                ];

                // Create a new model instance
                $memberFee = new MemberFee($rowData);
                $memberFee->timestamps = false;

                // Save the model
                $memberFee->save();

                // Commit the transaction every time the chunk size is reached
                if ($counter % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }

            // Commit any remaining changes in the database
            DB::commit();

            // Close the CSV file
            fclose($csvFile);
        } catch (\Throwable $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();

            // Close the CSV file
            fclose($csvFile);

            // Handle the error (e.g., log the error, display an error message, etc.)
            dd($e->getMessage());
        }
    }

    /**
     * تنسيق رقم العضوية وفقًا للقواعد المحددة.
     *
     * @param  string  $regNum
     * @return int|null
     */
    private function getMemberIdByRegNum($regNum)
    {
        $member = Member::where('RegNum', $regNum)->first();
        return $member ? $member->id : null;
    }
}
    