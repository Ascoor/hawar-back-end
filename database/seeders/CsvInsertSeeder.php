<?php

namespace Database\Seeders;

use App\Models\MemberFee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Open the CSV file for reading
        $csvFile = fopen(public_path('data/member_fees.csv'), 'r');

        // Begin the database transaction
        DB::beginTransaction();

        // Initialize Symfony ConsoleOutput
        $output = new ConsoleOutput();

        try {
            // Define the number of rows to process per chunk
            $chunkSize = 1000;

            // Initialize a counter
            $counter = 0;

            while (($data = fgetcsv($csvFile)) !== false) {
                // Increment the counter
                $counter++;

                // Display the current row being processed
                $output->writeln("Processing row: $counter");

                // Map the CSV data to your table columns
                $rowData = [
                    'Mem_ID' => $data[0],
                    'Mem_Name' => $data[1],
                    'Mem_Code' => $data[2],
                    'Mem_Address' => $data[3],
                    'Mem_HomePhone' => $data[4],
                    'Mem_Mobile' => $data[5],
                    'Mem_WorkPhone' => $data[6],
                    'Fee_ID' => $data[7],
                    'Fee_Year' => $data[8],
                    'Fee_Amount' => $data[9],
                    'Fee_Date' => $data[10],
                    'Fee_RecieptNumber' => $data[11],
                    'Fee_Status' => $data[12],

                ];


                // Create a new model instance
                $memberFee = new MemberFee($rowData);
                $memberFee->timestamps = false;
                // Save the model
                $memberFee->save();

                // Commit the transaction every chunkSize iterations
                if ($counter % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }

            // Commit any remaining changes
            DB::commit();

            // Close the CSV file
            fclose($csvFile);
        } catch (\Throwable $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Close the CSV file
            fclose($csvFile);

            // Handle the exception
            // (e.g., log the error, display an error message, etc.)
            dd($e->getMessage());
        }
    }
}
