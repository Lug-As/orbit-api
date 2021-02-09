<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AdType;
use App\Models\Age;
use App\Models\Country;
use App\Models\Offer;
use App\Models\Project;
use App\Models\Region;
use App\Models\Request;
use App\Models\Response;
use App\Models\Topic;
use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Sokol',
            'email' => 'skal.04@mail.ru',
            'password' => Hash::make('123123123'),
            'phone' => '8005553535',
            'is_admin' => true,
        ])->markEmailAsVerified();
        User::create([
            'name' => 'User',
            'email' => 'skal.03@mail.ru',
            'password' => Hash::make('123123123'),
            'phone' => '8005553536',
        ])->markEmailAsVerified();
        User::factory()->count(48)->create();
        $countries_data = include './storage/app/data/countries/countries.php';
        foreach ($countries_data as $country) {
            Country::create([
                'name' => $country,
            ]);
        }
        $regions_data = include './storage/app/data/regions/regions.php';
        foreach ($regions_data as $country_id => $regions_list) {
            foreach ($regions_list as $region) {
                $regionRecord = Region::create([
                    'name' => $region,
                    'country_id' => $country_id,
                ]);
                Account::factory()->count(random_int(0, 2))->create([
                    'region_id' => $regionRecord->id,
                ]);
                Request::factory()->count(random_int(0, 4))->create([
                    'region_id' => $regionRecord->id,
                ]);
            }
        }
        Project::factory()->count(100)->create();
        Response::factory()->count(100)->create();
        Offer::factory()->count(100)->create();
        $topics = ['Бизнес', 'Развлечение', 'Наука', 'Лайфхаки', 'Танцы'];
        $accounts = Account::all();
        $requests = Request::all();
        foreach ($topics as $topic) {
            $topicRecord = Topic::create([
                'name' => $topic,
            ]);
            foreach ($accounts as $account) {
                if (!random_int(0, 2)) {
                    $account->topics()->attach($topicRecord->id);
                }
            }
            foreach ($requests as $request) {
                if (!random_int(0, 2)) {
                    $request->topics()->attach($topicRecord->id);
                }
            }
        }
        $ages_data = include './storage/app/data/ages/ages.php';
        foreach ($ages_data as $age_range) {
            $ageRecord = Age::create([
                'range' => $age_range,
            ]);
            foreach ($accounts as $account) {
                if (!random_int(0, 2)) {
                    $account->ages()->attach($ageRecord->id);
                }
            }
            foreach ($requests as $request) {
                if (!random_int(0, 2)) {
                    $request->ages()->attach($ageRecord->id);
                }
            }
        }
        $projects = Project::all();
        $ad_types = ['Дуэт', 'Ссылка в шапке профиля', 'Видео', 'Танец', 'Реклама аудиотрека'];
        foreach ($ad_types as $ad_type) {
            $adTypeRecord = AdType::create([
                'name' => $ad_type,
            ]);
            foreach ($accounts as $account) {
                if (!random_int(0, 2)) {
                    $account->ad_types()->attach($adTypeRecord->id, ['price' => random_int(500, 100000)]);
                }
            }
            foreach ($projects as $project) {
                if (!random_int(0, 2)) {
                    $project->ad_types()->attach($adTypeRecord->id);
                }
            }
            foreach ($requests as $request) {
                if (!random_int(0, 2)) {
                    $request->ad_types()->attach($adTypeRecord->id, ['price' => random_int(50, 100000)]);
                }
            }
        }
    }
}
