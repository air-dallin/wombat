<?php

namespace Database\Seeders;

use App\Models\UserType;
use App\Models\City;
use App\Models\Document;
use App\Models\Event;
use App\Models\Livestock;
use App\Models\Permission;
use App\Models\Reviews;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Models\UserAdviser;
use App\Models\UserConsultant;
use App\Models\UserCrop;
use App\Models\UserLivestock;
use App\Models\UserRole;
use App\Models\UserService;
use App\Models\UserUniversity;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DB::table('users')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'password' => Hash::make('password'),
        ]); */

        User::factory()->count(10)->create();
        Livestock::factory()->count(10)->create();
        Categories::factory()->count(10)->create();
        News::factory()->count(10)->create();
        Event::factory()->count(10)->create();
        Service::factory()->count(10)->create();
        Crop::factory()->count(10)->create();
        City::factory()->count(10)->create();
        Region::factory()->count(10)->create();
        Role::factory()->count(10)->create();
        Permission::factory()->count(10)->create();
        Document::factory()->count(10)->create();
        Ticket::factory()->count(10)->create();
        Adviser::factory()->count(10)->create();
        UserType::factory()->count(10)->create();
        Reviews::factory()->count(10)->create();
        UserService::factory()->count(10)->create();
        UserConsultant::factory()->count(10)->create();
        UserCrop::factory()->count(10)->create();
        UserLivestock::factory()->count(10)->create();
        UserRole::factory()->count(10)->create();
        UserAdviser::factory()->count(10)->create();
        UserUniversity::factory()->count(10)->create();
        UserFarmer::factory()->count(10)->create();
        UserEvent::factory()->count(10)->create();


    }
}
