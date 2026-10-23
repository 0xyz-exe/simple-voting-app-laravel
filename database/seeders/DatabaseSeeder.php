<?php

namespace Database\Seeders;

use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
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
        $user = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);
        $voter = User::factory()->create(['name' => 'Voter User', 'email' => 'voter@example.com']);

        // Poll 1 — tech stack
        $poll1 = $user->polls()->create(['question' => 'What is your preferred PHP framework?', 'description' => 'Vote for your favourite!', 'is_active' => true]);
        $opts1 = collect(['Laravel', 'Symfony', 'CodeIgniter', 'Yii'])->map(fn($o) => $poll1->options()->create(['option_text' => $o]));
        Vote::create(['user_id' => $voter->id, 'poll_id' => $poll1->id, 'poll_option_id' => $opts1[0]->id]);

        // Poll 2 — multiple choice
        $poll2 = $user->polls()->create(['question' => 'Which features do you want next?', 'allow_multiple' => true, 'is_active' => true]);
        collect(['Dark Mode', 'Mobile App', 'API Access', 'Export to PDF', 'Email Notifications'])->each(fn($o) => $poll2->options()->create(['option_text' => $o]));

        // Poll 3 — closed
        $poll3 = $user->polls()->create(['question' => 'Best programming language of 2024?', 'is_active' => false]);
        $opts3 = collect(['Python', 'JavaScript', 'Rust', 'Go'])->map(fn($o) => $poll3->options()->create(['option_text' => $o]));
        Vote::create(['user_id' => $voter->id, 'poll_id' => $poll3->id, 'poll_option_id' => $opts3[1]->id]);
        Vote::create(['user_id' => $user->id, 'poll_id' => $poll3->id, 'poll_option_id' => $opts3[0]->id]);
    }
}
