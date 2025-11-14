<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user_id = 1 → sesuaikan kalau beda
        $user = 1;

        $data = [
            [
                'user_id' => $user,
                'title' => 'Kumpulkan laporan PBO',
                'description' => 'Upload ke LMS sebelum jam 5 sore',
                'deadline' => now()->subDays(1)->setTime(17,0), // lewat deadline
                'is_done' => true, // history
                'type' => 'task',
            ],
            [
                'user_id' => $user,
                'title' => 'Belanja keperluan skripsi',
                'description' => 'Beli kertas + map',
                'deadline' => now()->subDays(2)->setTime(18,0),
                'is_done' => true, // history
                'type' => 'task',
            ],
            [
                'user_id' => $user,
                'title' => 'Rapat kelompok TA',
                'description' => 'Bahas bab 3',
                'start_at' => now()->subDays(3)->setTime(10,0),
                'end_at' => now()->subDays(3)->setTime(12,0),
                'is_done' => true, // agenda selesai
                'type' => 'agenda',
            ],
            [
                'user_id' => $user,
                'title' => 'Kerjakan tugas basis data',
                'description' => 'ERD + normalisasi',
                'deadline' => now()->addDays(2)->setTime(23,0),
                'is_done' => false, // task aktif
                'type' => 'task',
            ],
            [
                'user_id' => $user,
                'title' => 'Meeting organisasi',
                'description' => 'Bahas event bulan depan',
                'start_at' => now()->addDays(1)->setTime(14, 0),
                'end_at' => now()->addDays(1)->setTime(16, 0),
                'is_done' => false,
                'type' => 'agenda',
            ],
        ];

        foreach ($data as $task) {
            Task::create($task);
        }
    }
}
