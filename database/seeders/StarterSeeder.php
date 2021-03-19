<?php

namespace Database\Seeders;

use App\Models\AdType;
use App\Models\Age;
use App\Models\Country;
use App\Models\Region;
use App\Models\Topic;
use Illuminate\Database\Seeder;

class StarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCountries();
        $this->seedRegions();
        $this->seedTopics();
        $this->seedAdTypes();
        $this->seedAges();
    }

    protected function seedCountries()
    {
        $countries_data = include './storage/app/data/countries/countries.php';
        foreach ($countries_data as $country) {
            Country::create([
                'name' => $country,
            ]);
        }
    }

    protected function seedRegions()
    {
        $regions_data = include './storage/app/data/regions/regions.php';
        foreach ($regions_data as $country_id => $regions_list) {
            foreach ($regions_list as $region) {
                Region::create([
                    'name' => $region,
                    'country_id' => $country_id,
                ]);
            }
        }
    }

    protected function seedTopics()
    {
        $topics = include './storage/app/data/topics/topics.php';
        foreach ($topics as $topic) {
            Topic::create([
                'name' => $topic,
            ]);
        }
    }

    protected function seedAdTypes()
    {
        $types = include './storage/app/data/ad_types/ad_types.php';
        foreach ($types as $type) {
            AdType::create([
                'name' => $type,
            ]);
        }
    }

    protected function seedAges()
    {
        $ranges = include './storage/app/data/ages/ages.php';
        foreach ($ranges as $range) {
            Age::create([
                'range' => $range,
            ]);
        }
    }
}
