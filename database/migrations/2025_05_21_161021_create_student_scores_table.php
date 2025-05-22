<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_scores', function (Blueprint $table) {
        $table->id();
        $table->string('sbd')->index(); // Số báo danh
        $table->float('toan')->nullable()->index();
        $table->float('ngu_van')->nullable()->index();
        $table->float('ngoai_ngu')->nullable()->index();
        $table->float('vat_li')->nullable()->index();
        $table->float('hoa_hoc')->nullable()->index();
        $table->float('sinh_hoc')->nullable()->index();
        $table->float('lich_su')->nullable()->index();
        $table->float('dia_li')->nullable()->index();
        $table->float('gdcd')->nullable()->index();
        $table->string('ma_ngoai_ngu')->nullable()->index();
        $table->timestamps();
// … tương tự cho các cột khác

    });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_scores');
    }
};

