<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deletion_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 32);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('summary');
            $table->json('details')->nullable();
            $table->string('deleted_by', 64)->nullable();
            $table->timestamp('deleted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_logs');
    }
};
