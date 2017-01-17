<?php

class ResellerTableSeeder extends Seeder {

    public function run()
    {
        DB::table('resellers')->delete();

        \App\Model\Reseller::create(array(
            'domain' => ''
        ));
    }
}