<?php

namespace Database\Seeders;

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

            // تهيئة عداد
            $counter = 0;

            while (($data = fgetcsv($csvFile)) !== false) {
                // زيادة العداد
                $counter++;

                // عرض الصف الحالي الذي يتم معالجته
                $output->writeln("جاري معالجة الصف: $counter");

                // ربط بيانات CSV بأعمدة الجدول
                $rowData = [
                    'Mem_ID' => $data[0],
                    'Mem_Name' => $data[1],
                    'Mem_Code' => $data[2],
                    'Mem_Address' => $data[3],
                    'Mem_HomePhone' => $this->formatPhoneNumber($data[4]),
                    'Mem_Mobile' => $this->formatPhoneNumber($data[5]),
                    'Mem_WorkPhone' => $this->formatPhoneNumber($data[6]),
                    'Fee_ID' => $data[7],
                    'Fee_Year' => $data[8],
                    'Fee_Amount' => $data[9],
                    'Fee_Date' => $data[10],
                    'Fee_RecieptNumber' => $data[11],
                    'Fee_Status' => $data[12],
                ];

                // إنشاء نموذج جديد
                $memberFee = new MemberFee($rowData);
                $memberFee->timestamps = false;

                // حفظ النموذج
                $memberFee->save();

                // التحويل في قاعدة البيانات كلما اكتملت الدفعة
                if ($counter % $chunkSize === 0) {
                    DB::commit();
                    DB::beginTransaction();
                }
            }

            // التحويل في قاعدة البيانات لأي تغييرات متبقية
            DB::commit();

            // إغلاق ملف CSV
            fclose($csvFile);
        } catch (\Throwable $e) {
            // إلغاء التحويل في حالة وجود خطأ
            DB::rollBack();

            // إغلاق ملف CSV
            fclose($csvFile);

            // التعامل مع الخطأ
            // (مثلاً، تسجيل الخطأ، عرض رسالة خطأ، إلخ)
            dd($e->getMessage());
        }
    }

    /**
     * تنسيق رقم الهاتف وفقًا للقواعد المحددة.
     *
     * @param  string  $phoneNumber
     * @return string
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
}
