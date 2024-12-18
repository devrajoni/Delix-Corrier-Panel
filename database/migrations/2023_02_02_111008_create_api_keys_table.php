<?php

use App\Models\ApiKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key');
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        $now  = now();
        $data = [
            [
                'title'      => 'Merchant App',
                'key'      => Str::random(16),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'Rider App',
                'key'      => Str::random(16),
                'created_at' => $now,
                'updated_at' => $now,
            ]
            ];
        ApiKey::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_keys');
    }
};
