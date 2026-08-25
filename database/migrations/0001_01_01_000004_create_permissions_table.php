<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid')->index();
            $table->string('name', 191)->unique();
            $table->string('group', 60)->index();
            $table->unsignedInteger('order')->default(0);
            $table->foreignId('module_id')->nullable()->index()->constrained('modules')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
