<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\RoleUser;
use App\Enums\StatusEnum;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Enums\PaymentMethodType;
class MFSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        PaymentMethod::create([
            'name'          => 'bKash',
            'type'          => PaymentMethodType::MFS->value,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        PaymentMethod::create([
            'name'          => 'Nagad',
            'type'          => PaymentMethodType::MFS->value,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        PaymentMethod::create([
            'name'          => 'Bangladesh Bank',
            'type'          => PaymentMethodType::BANK->value,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        PaymentMethod::create([
            'name'          => 'Offline',
            'type'          => PaymentMethodType::CASH->value,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

    }


}
