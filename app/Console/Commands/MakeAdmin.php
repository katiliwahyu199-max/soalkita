<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {email : Email akun yang akan dijadikan admin}';

    protected $description = 'Promosikan akun yang sudah ada menjadi admin aplikasi';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Akun tidak ditemukan. Daftarkan akun terlebih dahulu.');

            return self::FAILURE;
        }

        if (User::where('role', 'admin')->where('id', '!=', $user->id)->exists()) {
            $this->error('Admin sudah ada. Aplikasi ini hanya mendukung satu admin.');

            return self::FAILURE;
        }

        $user->forceFill(['role' => 'admin'])->save();
        $this->info("{$user->email} sekarang menjadi admin.");

        return self::SUCCESS;
    }
}