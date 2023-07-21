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

            // تهيئة عداد
            $counter = 0;

            while (($data = fgetcsv($csvFile)) !== false) {
                // زيادة العداد
                $counter++;

                // عرض الصف الحالي الذي يتم معالجته
                $output->writeln("جاري معالجة الصف: $counter");

                // ربط بيانات CSV بأعمدة الجدول
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
    private function getMemberIdByRegNum($regNum)
    {
        $member = Member::where('RegNum', $regNum)->first();
        return $member ? $member->id : null;
    }
}
