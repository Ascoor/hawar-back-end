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
            $lastProcessedRow = $this->getLastProcessedRow() + 1; // Get the last processed row number from the database
            $counter = 0;

            while (($data = fgetcsv($csvFile)) !== false) {
                $counter++;

                // Skip rows until the starting row number is reached
                if ($counter < $lastProcessedRow) {
                    continue;
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

                    // Store the last processed row number after processing the chunk
                    $this->storeLastProcessedRow($counter);
                }
            }

            // Commit any remaining changes in the database
            DB::commit();

            // Close the CSV file
            fclose($csvFile);

            // Store the last processed row number (end of the CSV file)
            $this->storeLastProcessedRow($counter);

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

    /**
     * Get the last processed row number from the database.
     *
     * @return int
     */
    private function getLastProcessedRow()
    {
        // Replace the following line with your logic to retrieve the last processed row number
        // from the database, for example, if you store the last row number in a table named `last_processed_row`:
        return DB::table('last_processed_row')->value('row_number');
        // For now, we'll return 0 as a placeholder.
        return 0;
    }

    /**
     * Store the last processed row number to the database.
     *
     * @param int $rowNumber
     * @return void
     */
    private function storeLastProcessedRow($rowNumber)
    {
        // Replace the following line with your logic to store the last processed row number
        // in the database, for example, if you store the last row number in a table named `last_processed_row`:
        DB::table('last_processed_row')->updateOrInsert(['id' => 1], ['row_number' => $rowNumber]);
        // For now, we'll leave it empty as a placeholder.
    }
}
