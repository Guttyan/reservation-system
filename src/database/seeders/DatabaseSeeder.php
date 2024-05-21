<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GenresTableSeeder::class);
        $this->call(AreasTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        $this->call(ShopGenreTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);

        // 管理者のユーザーを作成
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('11111111'),
            'email_verified_at' => now(),
        ]);

        // 管理者の役割を付与
        $user->assignRole('admin');
    }
}
