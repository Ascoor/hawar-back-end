<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_lookups', function (Blueprint $table) {
            $table->id(); // مفتاح رئيسي عددي لكل سجل
            $table->string('category'); // تصنيف الفئة (مثل: MembershipType, Class, Religion, Gender)
            $table->string('value'); // قيمة الاختيار (مثل: زوجة, والدة الزوج, مسلم, ذكر، إلخ)
            $table->timestamps(); // التوقيتات لتسجيل الإنشاء والتحديث

            // إضافة فهارس لتحسين أداء الاستعلامات
            $table->index(['category', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_lookups');
    }
}
