<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AddAdditionalSettingsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now  = now();
        $data = [
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
        ];

        // Conditional settings based on the environment variable
        if (env('VERIENT') == 'bd') {
            $data = array_merge($data, [
                [
                    'title'      => 'default_country',
                    'value'      => '19',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_currency',
                    'value'      => 'BDT',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_unit',
                    'value'      => 'kg',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_weight',
                    'value'      => '2',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_currency_symbol',
                    'value'      => '৳',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        } else {
            $data = array_merge($data, [
                [
                    'title'      => 'default_country',
                    'value'      => '233',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_currency',
                    'value'      => 'USD',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_unit',
                    'value'      => 'lb',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_weight',
                    'value'      => '1',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'title'      => 'default_currency_symbol',
                    'value'      => '$',
                    'lang'       => 'en',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }

        foreach ($data as $setting) {
            Setting::updateOrCreate(
                ['title' => $setting['title']],
                [
                    'value'      => $setting['value'],
                    'lang'       => $setting['lang'],
                    'updated_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Setting::whereIn('title', [
            'version_code',
            'version_title',
            'default_country',
            'default_currency',
            'default_unit',
            'default_weight',
            'default_currency_symbol'
        ])->delete();
    }
}
