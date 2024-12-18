<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use App\Enums\StatusEnum;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_histories', function (Blueprint $table) {
            $table->id();
            $table->string('to');
            $table->unsignedBigInteger('message_id')->nullable();
            $table->text('message')->nullable();
            $table->unsignedBigInteger('count')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_histories');
    }
};
