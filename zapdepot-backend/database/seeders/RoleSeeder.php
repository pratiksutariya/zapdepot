<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = $this->data();

        foreach ($data as $value) {
            Role::create([
                'name' => $value['name'],
                'guard_name' => $value['guard_name'],
            ]);
        }
    }

    public function data()
    {
        return [
            ['name' => 'admin','guard_name'=>'api'],
            ['name' => 'user','guard_name'=>'api'],
        ];
    }
}
