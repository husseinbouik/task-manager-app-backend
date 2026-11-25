<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Idempotent: safe to run on every deploy/boot (demo environment).
     * Demo login: demo@taskmanager.app / demo1234
     */
    public function run(): void
    {
        $demo = User::firstOrCreate(
            ['email' => 'demo@taskmanager.app'],
            ['name' => 'Demo User', 'password' => Hash::make('demo1234')]
        );

        $tasks = [
            ['title' => 'Welcome to Task Manager', 'description' => 'This demo workspace is seeded automatically. Create, edit and complete tasks to try it out.', 'status' => 'pending', 'due_date' => now()->addDays(2)->toDateString()],
            ['title' => 'Review project proposals', 'description' => 'Go through the three drafts and leave comments.', 'status' => 'pending', 'due_date' => now()->addDays(5)->toDateString()],
            ['title' => 'Prepare sprint board', 'description' => 'Move this week\'s priorities into the sprint.', 'status' => 'pending', 'due_date' => now()->subDay()->toDateString()],
            ['title' => 'Set up the demo environment', 'description' => 'Deployed and seeded on first boot.', 'status' => 'completed', 'due_date' => now()->subDays(2)->toDateString()],
        ];

        foreach ($tasks as $task) {
            Task::firstOrCreate(
                ['user_id' => $demo->id, 'title' => $task['title']],
                $task
            );
        }
    }
}
