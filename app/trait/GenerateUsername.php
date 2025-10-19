<?php

namespace App\trait;

use App\Models\User;

trait GenerateUsername
{
    public function generateUsername($name)
    {
        $base = strtolower(str_replace(' ', '', $name));
        // $username = $base . rand(1000, 9999);

        // while (User::where('username', $username)->exists()) {
        //     $username = $base . rand(1000, 9999);
        // }

        do {
            $username = $this->createRandomUsername($base);
        } while (User::where('username', $username)->exists());

        return $username;
    }

    protected function createRandomUsername(string $base): string
    {
        return $base . rand(1000, 9999);
    }
}
