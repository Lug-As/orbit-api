<?php

namespace Database\Seeders;

use App\Models\AdType;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->count(50)->create();
        \App\Models\Account::factory()->count(100)->create();
        \App\Models\Request::factory()->count(200)->create();
        \App\Models\Project::factory()->count(100)->create();
        \App\Models\Response::factory()->count(100)->create();
        \App\Models\Offer::factory()->count(100)->create();
        $topics = ['Бизнес', 'Развлечение', 'Наука', 'Лайфхаки', 'Танцы'];
        foreach ($topics as $topic) {
            Topic::create([
                'name' => $topic,
            ]);
        }
        $ad_types = ['Дуэт', 'Ссылка в шапке профиля', 'Видео', 'Танец'];
        foreach ($ad_types as $ad_type) {
            AdType::create([
                'name' => $ad_type,
            ]);
        }
    }
}
