<?php

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            // factory(UserAddress::class, random_int(1, 3)) 对每一个用户，产生一个 1 - 3 的随机数作为我们要为个用户生成地址的个数
            factory(UserAddress::class, random_int(1, 3))->create(['user_id' => $user->id]);
        });
    }
}
