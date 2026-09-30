<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        Badge::create([
            'name' => 'Getting Started',
            'description' => 'Complete your first code submission',
            'icon' => 'star',
            'points_required' => 0,
        ]);

        Badge::create([
            'name' => 'Bug Hunter',
            'description' => 'Find and fix 10 bugs in your code',
            'icon' => 'bug',
            'points_required' => 100,
        ]);

        Badge::create([
            'name' => 'Code Master',
            'description' => 'Get 50 perfect scores on submissions',
            'icon' => 'trophy',
            'points_required' => 500,
        ]);

        Badge::create([
            'name' => 'On Fire',
            'description' => 'Maintain a 7-day submission streak',
            'icon' => 'flame',
            'points_required' => 200,
        ]);

        Badge::create([
            'name' => 'Style Guru',
            'description' => 'Fix 20 style and formatting issues',
            'icon' => 'palette',
            'points_required' => 150,
        ]);

        Badge::create([
            'name' => 'Performance Hero',
            'description' => 'Optimize 15 performance issues',
            'icon' => 'zap',
            'points_required' => 250,
        ]);

        Badge::create([
            'name' => 'Security Guardian',
            'description' => 'Fix 5 security vulnerabilities',
            'icon' => 'shield',
            'points_required' => 300,
        ]);

        Badge::create([
            'name' => 'Dedicated Learner',
            'description' => 'Complete 30 code submissions',
            'icon' => 'book',
            'points_required' => 350,
        ]);
    }
}
