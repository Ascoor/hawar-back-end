<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberFee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class OldMembersSeeder extends Seeder
{
    /**
     * Format the phone number.
     *
     * @param string $phoneNumber الرقم الذي يجب تنسيقه.
     * @return string الرقم المنسق بالطريقة المطلوبة.
     */
    function formatPhoneNumber($phoneNumber)
    {
        // إزالة أي حروف غير رقمية من الرقم
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // التحقق من طول الرقم
        if (strlen($phoneNumber) === 11) {
            // التحقق مما إذا كان الرقم يبدأ بـ 011، 012، 010، أو 015
            if (preg_match('/^(011|012|010|015)/', $phoneNumber)) {
                return $phoneNumber;
            } elseif (substr($phoneNumber, 0, 3) === '018') {
                // تغيير البادئة إلى 0128
                return '0128' . substr($phoneNumber, 3);
            }
        } elseif (strlen($phoneNumber) === 10) {
            // التحقق مما إذا كان الرقم يبدأ بـ 010
            if (substr($phoneNumber, 0, 3) === '010') {
                return '0100' . substr($phoneNumber, 3);
            } elseif (substr($phoneNumber, 0, 3) === '016') {
                // تغيير البادئة إلى 0106
                return '0106' . substr($phoneNumber, 3);
            } elseif (substr($phoneNumber, 0, 3) === '100') {
                // تغيير البادئة إلى 0100
                return '0100' . substr($phoneNumber, 3);
            } elseif (substr($phoneNumber, 0, 3) === '10') {
                // تغيير البادئة إلى 010
                return '010' . substr($phoneNumber, 3);
            } elseif (substr($phoneNumber, 0, 3) === '012') {
                // تغيير البادئة إلى 0122
                return '0122' . substr($phoneNumber, 3);
            }
        } elseif (strlen($phoneNumber) === 9 && $phoneNumber[0] !== '0') {
            // إضافة صفر في البداية وتطبيق القواعد مرة أخرى
            return $this->formatPhoneNumber('0' . $phoneNumber);
        }
        // إذا لم تتطابق أي من القواعد، استعيد الرقم الأصلي
        return $phoneNumber;
    }

    private function calculateAge($bod)
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($bod);

        // Calculate the age based on the birth date
        $age = $carbonDate->diffInYears(Carbon::now());

        return $age;
    }

    private function generateEmail($regNum)
    {
        return 'h-mem' . $regNum . '@el-hawar.com';
    }
    // }
    public function run()
    {
        // فتح ملف CSV للقراءة
        $csvFile = fopen(public_path('data/members.csv'), 'r');

        // بدء عملية المعاملة القاعدة
        DB::beginTransaction();

        // تهيئة Symfony ConsoleOutput
        $output = new ConsoleOutput();

        try {
            // تخطي الصف العنواني
            fgetcsv($csvFile);

            // تحديد عدد الصفوف للمعالجة في كل مجموعة
            $chunkSize = 1000;

            // تهيئة عداد
            $counter = 0;
            while (($data = fgetcsv($csvFile)) !== false) {
                // زيادة العداد
                $counter++;

                // عرض الصف الحالي الذي يتم معالجته
                $output->writeln("جارٍ معالجة الصف: $counter");
            // Create the 'notes' array
            $notes = [
                [
                    'createdBy' => '1',
                    'note' => $data[14], // Note1
                    'createdAt' => now()->toDateTimeString(), // Set createdAt to the current date and time
                ],
                [
                    'createdBy' => '1',
                    'note' => $data[15], // Note2
                    'createdAt' => now()->toDateTimeString(), // Set createdAt to the current date and time
                ],
                [
                    'createdBy' => '1',
                    'note' => $data[16], // Note3
                    'createdAt' => now()->toDateTimeString(), // Set createdAt to the current date and time
                ],
                [
                    'createdBy' => '1',
                    'note' => $data[17], // Note4
                    'createdAt' => now()->toDateTimeString(), // Set createdAt to the current date and time
                ],
            ];

            $rowData = [
                'member_id' => $data[3],
                'name' => $data[1],
                'family_id' => $data[2],
                'date_of_birth' => $data[4],
                'national_id' => $data[5],
                'user_id' => '1', // Set the user_id to 1 for all data
                'gender' => $data[6],
                'category_id' => $data[7],
                'relation_id' => $data[8],
                'status_id' => $data[9],
                'phone' => $data[10],

                'address' => $data[11],
                'profession' => $data[12],
                'relegion' => $data[13],
                'postal_code' => '35111',
                'notes' => json_encode($notes), // Convert the 'notes' array to JSON
                'last_payed_fiscal_year'=>$data[18],
                'date_of_the_board_of_director_Decisions'=>$data[19],
                'decision_number'=>$data[20],
                'memCard_MemberName'=>$data[21],
                    'mem_GraduationDesc'=>$data[22],
                    'mem_WorkPhone'=>$data[23],
                    'mem_HomePhone'=>$data[24],

           'Photo'=>$data[25],
           'date_of_subscription'=>$data[26],
           'excluded_categories_id'=>$data[27]

            ];
                // Insert data into the members table
                $member = new Member($rowData);
                $member->timestamps = false;
                $member->save();

          // Format the phone number
          $formattedPhoneNumber = $this->formatPhoneNumber($data[10]);
          $rowData['phone'] = $formattedPhoneNumber;

          // Calculate the age based on the date of birth
          $age = $this->calculateAge($data[4]);
          // You can use the age in your logic as needed.

          // Generate email address for the member
          $regNum = $data[3]; // Replace this with the appropriate field that holds the registration number.
          $email = $this->generateEmail($regNum);
          $rowData['email'] = $email;

          // Determine the sub_category_id based on the religion
          $subCategoryId = null;
          if ($data[13] === 'مسلم') {
              $subCategoryId = 1;
          } elseif ($data[13] === 'مسيحى') {
              $subCategoryId = 2;
          }

          // Insert data into the member_category table based on the religion
          if ($subCategoryId !== null) {
              $memberCategoryData = [
                  'member_id' => $member->id,
                  'category_id' => 6, // Assuming this is the category_id for the religion category
                  'sub_category_id' => $subCategoryId,
              ];

              DB::table('member_category')->insert($memberCategoryData);
          }
//                 // Map gender names to their corresponding sub_category_ids
// $genderSubCategoryMap = [
//     'ذكر' => 1,
//     'أنثى' => 2,
// ];

// // Assuming that $data[6] contains the gender, you can use the following code to check if it exists in the genderSubCategoryMap
// $gender = $data[13];

// // Check if the gender exists in the genderSubCategoryMap
// if (array_key_exists($gender, $genderSubCategoryMap)) {
//     // If the gender exists in the genderSubCategoryMap, use the corresponding sub_category_id
//     $subCategoryId = $genderSubCategoryMap[$gender];

//     // Check if the combination of member_id and sub_category_id already exists in the member_category table
//     $existingData = DB::table('member_category')
//         ->where('member_id', $member->id)
//         ->where('sub_category_id', $subCategoryId)
//         ->first();

//     if (!$existingData) {
//         // If the combination does not exist, insert the data into the member_category table
//         $memberCategoryData = [
//             'member_id' => $member->id,
//             'category_id' => 5, // Set category_id to 5 as it's the default value for all
//             'sub_category_id' => $subCategoryId,
//         ];
//         DB::table('member_category')->insert($memberCategoryData);
//     }
// }

//                 // Map subcategory names to their corresponding IDs
// $subCategoryMap = [
//     'عضو عامل' => 1,
//     'عضو تابع' => 2,
//     'عضو مؤسس' => 3,
//     'عضو فخري' => 4,
//     'عضو موسمي' => 5,
//     'عضو رياضى' => 7,
//     'تصريح' => 8,
// ];

// // Assuming that $data[11] contains the subcategory name, you can use the following code to check if it exists in the subCategoryMap
// $subCategoryName = $data[11];

// // Check if the subcategory exists in the subCategoryMap
// if (array_key_exists($subCategoryName, $subCategoryMap)) {
//     // If the subcategory exists in the subCategoryMap, use the corresponding sub_category_id
//     $subCategoryId = $subCategoryMap[$subCategoryName];

//     // Check if the combination of member_id and sub_category_id already exists in the member_category table
//     $existingData = DB::table('member_category')
//         ->where('member_id', $member->id)
//         ->where('sub_category_id', $subCategoryId)
//         ->first();

//     if (!$existingData) {
//         // If the combination does not exist, insert the data into the member_category table
//         $memberCategoryData = [
//             'member_id' => $member->id,
//             'category_id' => 1, // Set category_id to 1 as it's the default value for all
//             'sub_category_id' => $subCategoryId,
//         ];
//         DB::table('member_category')->insert($memberCategoryData);
//     }
// }

                // تنفيذ عملية المعاملة كل مجموعة من الصفوف
                if ($counter % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }

            // حفظ أي تغييرات متبقية
            DB::commit();

            // إغلاق ملف CSV
            fclose($csvFile);
        } catch (\Throwable $e) {
            // التراجع عن عملية المعاملة في حالة حدوث خطأ
            DB::rollBack();

            // إغلاق ملف CSV
            fclose($csvFile);

            // التعامل مع الاستثناء (مثل تسجيل الخطأ، عرض رسالة خطأ، إلخ)
            dd($e->getMessage());
        }
    }
    // private function convertToArabicDate($date)
    // {
    //     // Parse the date using Carbon
    //     $carbonDate = Carbon::parse($date);

    //     // Format the date using Arabic numbers in "yyyy-mm-dd" format
    //     $arabicDate = $carbonDate->locale('ar')->isoFormat('YYYY-MM-DD');

    //     return $arabicDate;
    // }
}
