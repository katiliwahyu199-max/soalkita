<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $name = config('services.admin.name', 'Administrator');
        $email = config('services.admin.email');
        $password = config('services.admin.password');
        $console = $this->command;

        if (blank($email) && $console) {
            $name = $console->ask('Admin name', $name);
            $email = $console->ask('Admin email');
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Set a valid ADMIN_EMAIL or run AdminUserSeeder interactively.');
        }

        $user = User::where('email', $email)->first();
        $otherAdmins = User::where('role', 'admin');

        if ($user) {
            $otherAdmins->where('id', '!=', $user->id);
        }

        if ($otherAdmins->exists()) {
            throw new RuntimeException('An admin account already exists with a different email.');
        }

        if (! $user && blank($password)) {
            if ($console) {
                $password = $console->secret('Admin password');
                $confirmation = $console->secret('Confirm admin password');

                if (! hash_equals((string) $password, (string) $confirmation)) {
                    throw new RuntimeException('Admin passwords do not match.');
                }
            } else {
                throw new RuntimeException('Set ADMIN_PASSWORD before creating the admin account.');
            }
        }

        if (! $user && blank($password)) {
            throw new RuntimeException('Admin password cannot be empty.');
        }

        if (! $user) {
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->email_verified_at = now();
        }

        $user->role = 'admin';
        $user->save();

        $this->command?->info("Admin account ready: {$email}");
    }
}