<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first(); // asumsi sudah ada user

        if (!$user) return;

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Task Baru',
            'message' => 'Kamu mendapatkan task baru hari ini.',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title'   => 'Reminder',
            'message' => 'Jangan lupa selesaikan task sebelum deadline.',
        ]);
    }
}
