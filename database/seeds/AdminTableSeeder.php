<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = $this->dataDevelopment();

        if (\App::environment() === 'production') {
            $admins = $this->dataProduction();
        }

        Admin::truncate();

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }

    /**
     * get production seeder base on env variable APP_ENV (.env file)
     *
     * @return Array
     */
    public function dataProduction()
    {
        $password = bcrypt('password');

        return [
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@chronostep.com',
                'password' => $password
            ],
        ];
    }

    /**
     * get development seeder base on env variable APP_ENV (.env file)
     *
     * @return Array
     */
    public function dataDevelopment()
    {
        $password = bcrypt('password');
        
        return [
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@chronostep.com',
                'password' => $password,
            ]
        ];
    }
}
