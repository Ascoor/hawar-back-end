<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberFee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Open the CSV file for reading
        $csvFile = fopen(public_path('data/members.csv'), 'r');

        // Begin the database transaction
        DB::beginTransaction();

        // Initialize Symfony ConsoleOutput
        $output = new ConsoleOutput();

        try {
            // Skip the header row
            fgetcsv($csvFile);

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
              // Map the CSV data to your table columns
// Map the CSV data to your table columns
$rowData = [

    'Mem_Name' => $data[2],
    'Mem_Code' => $data[3],
    'Mem_BOD' => $data[4],
    'Mem_NID' => $data[5],
    'Graduation' => $data[6],
    'Mem_ParentMember' => $data[7],
    'Gender' => $data[8],
    'Mem_Job' => $data[9],
    'JobCategory' => $data[10],
    'MembershipType' => $data[11],
    'Relegion' => $data[12],
    'Mem_Address' => $data[13],
    'Mem_JoinDate' => $data[14],
    'Class' => $data[15],
    'Mem_HomePhone' => $data[16],
    'Mem_Mobile' => $data[17],
    'Mem_Receiver' => $data[18],
    'Mem_WorkPhone' => $data[19],
    'Mem_Photo' => $data[20],
    'Mem_Notes' => $data[21],
    'Mem_LastPayedFees' => $data[22],
    'Status' => $data[23],
    'MemCard_MemberName' => $data[24],
    'MemCard_MemberJobTitle' => $data[25],
    'Mem_GraduationDesc' => $data[26],
    'Mem_Notes_2' => $data[27],
    'Mem_Notes_3' => $data[28],
    'Mem_Notes_4' => $data[29],
    'Mem_Relation' => $data[30],
    'parentName' => $data[31],
    'Mem_IsMainMember' => $data[32],
    'Mem_BoardDecision_Date' => $data[33],
    'Mem_BoardDecision_Number' => $data[34],
];



                // Create a new model instance
                $member = new Member($rowData);
                $member->timestamps = false;
                // Save the model
                $member->save();

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
