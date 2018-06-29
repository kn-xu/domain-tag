<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Domains;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(UsersTableSeeder::class);
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $domain = new Domains();
            $domain->domain = $faker->unique()->domainName;
            $domain->description = $faker->paragraph;
            $domain->flag_status = 0;
            $domain->save();
            unset($domain);

            sleep(1);
        }
    }
}
