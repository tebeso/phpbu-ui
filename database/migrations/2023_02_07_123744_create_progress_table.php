<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('progress', static function (Blueprint $table) {
            $table->id();
            $table->integer('shell')->default(1);
            $table->uuid();
            $table->integer('backup_id');
            $table->string('backup_type');
            $table->string('command');
            $table->string('created_by');
            $table->longText('log')->nullable();
            $table->integer('completed')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};