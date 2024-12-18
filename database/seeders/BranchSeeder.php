<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Branch;
use App\Models\User;
use App\Models\RoleUser;
use App\Enums\StatusEnum;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Branch::create([
            'user_id'       => '1',
            'name'          => 'Main Branch',
            'address'       => '123 Main St, City, Country',
            'phone_number'  => '123-456-7890',
            'status'        => StatusEnum::ACTIVE->value,
            'default'       => 1,
            'created_by'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        Branch::create([
            'user_id'       => '2',
            'name'          => 'Outside City',
            'address'       => '123 Main St, City, Country',
            'phone_number'  => '123-456-7890',
            'status'        => StatusEnum::ACTIVE->value,
            'default'       => 0,
            'created_by'    => 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);


        $user                   = User::find(1);
        $user->branch_id        = 1;
        $user->save();

        $user                   = User::find(2);
        $user->branch_id        = 2;
        $user->save();

    }


}
