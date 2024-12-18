<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('value')->nullable();
            $table->string('lang')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();

        });

        $now  = now();


        $data = [
            [
                'title'      => 'return_charge',
                'value'      => '40',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'fragile_charge',
                'value'      => '20',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'pickup_accept_start',
                'value'      => '24',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'pickup_accept_end',
                'value'      => '"BDT"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'outside_dhaka_days',
                'value'      => '8',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'paginate_all_list',
                'value'      => '20',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'paginate_parcel_merchant_list',
                'value'      => '50',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'paginate_api_list',
                'value'      => '15',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'sms_cli',
                'value'      => '',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'api_key',
                'value'      => 'xxxxxxxxxxx',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'return_charge_dhaka',
                'value'      => '50',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'return_charge_sub_city',
                'value'      => '90',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'return_charge_outside_dhaka',
                'value'      => '120',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'return_charge_type',
                'value'      => 'on_demand',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'delivery_otp',
                'value'      => 'none',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'title'      => 'sip_domain',
                'value'      => '103.103.35.164:3939',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'version_code',
                'value'      => '100',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'version_title',
                'value'      => '1.0.0',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'update_skipable',
                'value'      => 'true',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'update_url',
                'value'      => '#',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'phone_visible',
                'value'      => 'true',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'title'      => 'admin_panel_title',
                'value'      => '"Delix"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'system_short_name',
                'value'      => '"Delix"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'admin_panel_copyright_text',
                'value'      => '"Copyright @2024 by SpaGreen Creative"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'admin_logo',
                'value'      => 'a:6:{s:7:\"storage\";s:5:\"local\";s:14:\"original_image\";s:39:\"images/20240128113929-admin_logo488.png\";s:11:\"image_80X80\";s:44:\"images/20240128113929-admin_logo-80X8077.png\";s:13:\"image_500x500\";s:0:\"\";s:12:\"image_100x36\";s:46:\"images/20240128113929-admin_logo-100x36160.png\";s:14:\"image_1200x630\";s:0:\"\";}',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'system_name',
                'value'      => '"Delix"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'company_name',
                'value'      => '"Delix"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'tagline',
                'value'      => '"Parcel Delivery System"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'title'      => 'phone',
                'value'      => '"01747436390"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'phone_country_id',
                'value'      => '"19"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'email_address',
                'value'      => '"delix@gmail.com"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'time_zone',
                'value'      => '1',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_language',
                'value'      => '"en"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_country',
                'value'      => '"19"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_currency',
                'value'      => '"BDT"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_unit',
                'value'      => '"kg"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_weight',
                'value'      => '"2"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'title'      => 'default_currency_symbol',
                'value'      => '"৳"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'currency_postion',
                'value'      => '"before"',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'title'      => 'theme_color',
                'value'      => 'red',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'default_delivery_area',
                'value'      => 'next_day',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'return_charge_city',
                'value'      => '60',
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'is_recaptcha_activated',
                'value'      => 1,
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => 'recaptcha_site_key',
                'value'      => 1,
                'lang'       => '6LfO41MqAAAAAC7D_A0Va4aQZJHoXMmWk0QKsrI8',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title'      => '6LfO41MqAAAAAJf-jyJseS2TXhdPUohLDov86hFi',
                'value'      => 1,
                'lang'       => 'en',
                'created_at' => $now,
                'updated_at' => $now,
            ],


        ];

        Setting::insert($data);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
