<?php

namespace Database\Seeders;

use App\Models\Member;
use Carbon\Carbon;
use Exception;
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
    private function formatPhoneNumber($phoneNumber)
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

    /**
     * Calculate the age based on the birthdate.
     *
     * @param string $bod تاريخ الميلاد بتنسيق "YYYY-MM-DD".
     * @return int العمر المحسوب بناءً على تاريخ الميلاد.
     */
    private function calculateAge($bod)
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($bod);

        // Calculate the age based on the birth date
        $age = $carbonDate->diffInYears(Carbon::now());

        return $age;
    }

    /**
     * Generate an email address based on the registration number.
     *
     * @param string $regNum رقم التسجيل.
     * @return string عنوان البريد الإلكتروني المُولَّد.
     */
    private function generateEmail($regNum)
    {
        return 'h-mem' . $regNum . '@el-hawar.com';
    }

    /**
     * Convert a given date to an Arabic date format "yyyy-mm-dd".
     *
     * @param string $date التاريخ بتنسيق "YYYY-MM-DD".
     * @return string التاريخ المحوّل بالأرقام العربية وبتنسيق "yyyy-mm-dd".
     */
    private function convertToArabicDate($date)
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($date);

        // Format the date using Arabic numbers in "yyyy-mm-dd" format
        $arabicDate = $carbonDate->locale('ar')->isoFormat('YYYY-MM-DD');

        return $arabicDate;
    }

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

               

                // Create the 'notes' JSON object
                $notes = [];
                $notes[] = [
                    'createdBy' => '1',
                    'note' => $data[22], // Note1
                    'createdAt' => $data[22] ? $this->convertToArabicDate($data[22]) : now()->toDateTimeString(),
                ];
                $notes[] = [
                    'createdBy' => '1',
                    'note' => $data[23], // Note2
                    'createdAt' => $data[28] ? $this->convertToArabicDate($data[28]) : now()->toDateTimeString(),
                ];
                $notes[] = [
                    'createdBy' => '1',
                    'note' => $data[24], // Note3
                    'createdAt' => $data[30] ? $this->convertToArabicDate($data[30]) : now()->toDateTimeString(),
                ];
                $notes[] = [
                    'createdBy' => '1',
                    'note' => $data[25], // Note4
                    'createdAt' => $data[31] ? Carbon::createFromFormat('Y-m-d', $data[31])->format('Y-m-d') : now()->toDateTimeString(),
                ];
                

                // Assign the 'notes' JSON object to the $rowData array
                $rowData['notes'] = $notes;

                // Map gender names to their corresponding sub_category_ids
                $genderSubCategoryMap = [
                    'ذكر' => 1,
                    'أنثى' => 2,
                ];

                // Assuming that $data[7] contains the gender, you can use the following code to check if it exists in the genderSubCategoryMap
                $gender = $data[7];

                // Check if the gender exists in the genderSubCategoryMap
                if (array_key_exists($gender, $genderSubCategoryMap)) {
                    // If the gender exists in the genderSubCategoryMap, use the corresponding sub_category_id
                    $subCategoryId = $genderSubCategoryMap[$gender];
                    
                    // Replace the 'Gender' value with the sub_category_id
                    $rowData['Gender'] = $subCategoryId;
                }
                
                // Insert data into the members table
                $rowData = [
                    'Name' => $data[1],
                    'MemberId' => $data[2],
                    'DayOfBirth' => $this->convertToArabicDate($data[3]),
                    'Age' => $this->calculateAge($data[3]), // Calculate age from the BOD column
                    'NationalId' => $data[4],
                    'RelationId' => $data[5],
                    'FamilyId' => $data[6],
                    'CategoryId' => $data[7],
                    'Address' => $data[8],
                    'CountryId' => '63',    
                    'City' => 'المنصورة',
                    'State' => 'الدقهلية',
                    'Gender' => $data[9],
                    'Profession' => $data[10],
                    'Relegion' => $data[11],
                    
                    'CreatedAt' => $this->convertToArabicDate($data[12]),
                    'DateOfSubscription' => $this->convertToArabicDate($data[12]),
                    
                    'HomePhone' => $data[13],
                    'Phone' => $this->formatPhoneNumber($data[14]),
                    'WorkPhone' => $data[15],
                    'PostalCode' => '35111',
                    'Photo' => $data[16],
                    'Status_Id' => $data[17],
                    'LastPayedFees' => $data[18],
                    'MemberCardName' => $data[19],
                    'MemberGraduationDescription' => $data[20],
                    'JobCategory' => $data[21],

                    'Notes' => $notes, // ملاحظات باللغة العربية

                    'ExcludedCategories' => $data[15],
                    'RenewalStatus' => $rowData['RenewalStatus'], // Set RenewalStatus based on Remarks
                    'Remarks' => $this->convertToArabicDate($data[33]),
                    'Email' => $this->generateEmail($data[3]), // Generate email using RegNum column
                   
                  
                ];

                // Insert data into the members table
                $member = new Member($rowData);
                $member->timestamps = false;
                $member->save();

                // Insert data into the member_category table based on the religion
                if ($data[12] === 'مسلم') {
                    $categoryId = 6;
                    $subCategoryId = 1;
                } elseif ($data[12] === 'مسيحى') {
                    $categoryId = 6;
                    $subCategoryId = 2;
                } else {
                    // إذا كانت القيمة غير "مسلم" أو "مسيحي" يمكنك تعيين قيم افتراضية أخرى أو تجاوز القيمة في حالة عدم الحاجة إلى إضافة تصنيفات رئيسية وفرعية
                    $categoryId = null;
                    $subCategoryId = null;
                }

                // Check if there are values for the category and subcategory, then insert into member_category table
                if ($categoryId !== null && $subCategoryId !== null) {
                    $memberCategoryData = [
                        'member_id' => $member->id,
                        'category_id' => $categoryId,
                        'sub_category_id' => $subCategoryId,
                    ];

                    DB::table('member_category')->insert($memberCategoryData);
                }

            }
        } catch (\Throwable $e) {
            // التراجع عن عملية المعاملة في حالة حدوث خطأ
            DB::rollBack();

            // إغلاق ملف CSV
            fclose($csvFile);

            // التعامل مع الاستثناء (مثل تسجيل الخطأ، عرض رسالة خطأ، إلخ)
            dd($e->getMessage());
        }
    }
}