<?php

use App\Models\SMSCredit;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the `sms_credits` table
        Schema::create('sms_credits', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('sms_package_id')->nullable();
            $table->longText('description')->nullable();
            $table->integer('quantity');
            $table->tinyInteger('type')->default(1)->comment('0 non-masking, 1 masking');
            $table->timestamps();
        });

        // Data to insert
        $data = [
            [
                'title'       => 'Free Credit',
                'description' => 'Free SMS Credit by Delix',
                'quantity'    => 100,
                'type'        => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]
        ];

        SMSCredit::insert($data);
        $creditQuantity        = $data[0]['quantity'];
        $sms_calculate         = Setting::where('title', 'total_credit')->first();

        if ($sms_calculate) {
            $sms_calculate->value += $creditQuantity;
        } else {
            $sms_calculate        = new Setting();
            $sms_calculate->title = 'total_credit';
            $sms_calculate->value = $creditQuantity;
            $sms_calculate->lang  =  'en';

        }


        // Save the setting
        $sms_calculate->save();

        Artisan::call('optimize:clear');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_credits');
    }
};
