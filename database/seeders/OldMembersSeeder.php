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
        // Generate the email using the pattern "h-mem+$RegNum@el-hawar.com"
        $email = 'h-mem' . $regNum . '@el-hawar.com';

        return $email;
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

                // عرض الصف الحالي الذي يتم معالجته
                $output->writeln("جارٍ معالجة الصف: $counter");

                $email = 'h-mem' . $data[3] . '@el-hawar.com';
                $remarksDate = Carbon::parse($data[33]);
                $currentYear = Carbon::now()->year;
                if ($remarksDate->year > $currentYear) {
                    $rowData['RenewalStatus'] = 'renewed';
                } else {
                    $rowData['RenewalStatus'] = 'unrenewed';
                }
                $rowData = [
                    'RegNum' => $data[3],
                    'Name' => $data[2],
                    'FamilyId' => $data[34],
                    'Category' => $data[11],
                    'Relation' => $data[30],
                    'Address' => $data[13],
                    'City' => 'المنصورة',
                    'State' => 'الدقهلية',
                    'CountryId' => '63',
                    'PostalCode' => '35111',
                    'Profession' => $data[9],
                    'JobCategory' => $data[10],
                    'Status' => $data[23],
                    'ExcludedCategories' => $data[15],
                    'Phone' => $this->formatPhoneNumber($data[17]),
                    'CreatedAt' => $this->convertToArabicDate($data[14]),
                    'NationalId' => $data[5],
                    'BOD' => $this->convertToArabicDate($data[4]),
                    'Relegion' => $data[12],
                    'DateOfSubscription' => $this->convertToArabicDate($data[14]),
                    'HomePhone' => $data[16],
                    'WorkPhone' => $data[19],
                    'MemberCardName' => $data[24],
                    'MemberGraduationDescription' => $data[26],
                    'RenewalStatus' => $rowData['RenewalStatus'], // Set RenewalStatus based on Remarks
                    'Remarks' => $this->convertToArabicDate($data[33]),
                    'Note2' => $data[27],
                    'Note3' => $data[28],
                    'Note4' => $data[29],
                    'Age' => $this->calculateAge($data[4]), // Calculate age from the BOD column
                    'Email' => $this->generateEmail($data[3]), // Generate email using RegNum column
                    'Gender' => $data[8],
                    'Receiver' => $data[18],
                    'Photo' => $data[20],
                    'Note1' => $data[21], // ملاحظات باللغة العربية
                    'LastPayedFees' => $data[22],
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
    private function convertToArabicDate($date)
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($date);

        // Format the date using Arabic numbers in "yyyy-mm-dd" format
        $arabicDate = $carbonDate->locale('ar')->isoFormat('YYYY-MM-DD');

        return $arabicDate;
    }
}
