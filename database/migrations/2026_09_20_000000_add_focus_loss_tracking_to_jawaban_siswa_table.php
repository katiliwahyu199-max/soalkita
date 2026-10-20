<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->unsignedInteger('focus_loss_count')->default(0)->after('jawaban_json');
            $table->timestamp('last_focus_loss_at')->nullable()->after('focus_loss_count');
        });
    }

    public function down(): void
    {
        Schema::table('jawaban_siswa', function (Blueprint $table) {
            $table->dropColumn(['focus_loss_count', 'last_focus_loss_at']);
        });
    }
};
