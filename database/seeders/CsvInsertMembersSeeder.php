<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\MemberFee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvInsertMembersSeeder extends Seeder
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

    /**
     * Run the database seeds.
     *
     * @return void
     */
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

                // ربط بيانات CSV بأعمدة الجدول
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
                    'Mem_JoinDate' => $this->convertToArabicDate($data[14]),
                    'Class' => $data[15],
                    'Mem_HomePhone' => $data[16],
                    'Mem_Mobile' => $this->formatPhoneNumber($data[17]),
                    'Mem_Receiver' => $data[18],
                    'Mem_WorkPhone' => $data[19],
                    'Mem_Photo' => $data[20],
                    'Mem_Notes' => $data[21], // ملاحظات باللغة العربية
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

                // إنشاء نموذج جديد
                $member = new Member($rowData);
                $member->timestamps = false;
                // حفظ النموذج
                $member->save();

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
