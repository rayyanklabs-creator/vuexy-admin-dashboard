<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        $this->call([
            UserRolePermissionSeeder::class,
        ]);
        User::factory(5000)
            ->create()
            ->each(function ($user) {
                $user->profile()->create([
                    'first_name' => $user->name,
                ]);

                // optionally assign role
                $user->assignRole('user');
            });
    }
}
