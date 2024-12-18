<?php

use App\Models\ApiKeyLanguage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_key_languages', function (Blueprint $table) {
            $table->id();
            $table->string('api_key_id');
            $table->string('title')->nullable();
            $table->string('lang', 10)->default('en');
            $table->timestamps();
        });
        $now  = now();
        $data = [
            [
                'api_key_id'        => 1,
                'title'               => 'Merchant App',
                'lang'              => 'en',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'api_key_id'        => 2,
                'title'             => 'Rider App',
                'lang'              => 'en',
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];
        ApiKeyLanguage::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_key_languages');
    }
};
