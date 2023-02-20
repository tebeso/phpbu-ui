<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('backups', static function (Blueprint $table) {
            $table->id();
            $table->string('server');
            $table->string('filename');
            $table->bigInteger('size');
            $table->integer('deleted')->default(0);
            $table->bigInteger('file_created_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('backup');
    }
};
