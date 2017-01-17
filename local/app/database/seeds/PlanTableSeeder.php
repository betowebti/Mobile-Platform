<?php

class PlanTableSeeder extends Seeder {

    public function run()
    {
        DB::table('plans')->delete();

        \App\Model\Plan::create(array(
            'reseller_id' => 1,
            'name' => 'Free',
            'sort' => 10,
            'undeletable' => 1
        ));

		\App\Model\Plan::create(array(
            'reseller_id' => 1,
            'name' => 'Standard',
            'sort' => 20,
            'settings' => '{"max_apps":"0","support":"-","domain":"1","download":"1","widgets":["about-us","call-us","catalogs","contact-us","content","coupons","e-commerce","email-us","events","facebook","flickr","forms","home-screen","instagram","loyalty-cards","map","photos","rss","soundcloud","twitter","vcard","video","web-page","youtube"],"monthly":"12","annual":"6","currency":"USD","featured":"0"}'
        ));

		\App\Model\Plan::create(array(
            'reseller_id' => 1,
            'name' => 'Deluxe',
            'sort' => 30,
            'settings' => '{"max_apps":"0","support":"-","domain":"1","download":"1","widgets":["about-us","call-us","catalogs","contact-us","content","coupons","e-commerce","email-us","events","facebook","flickr","forms","home-screen","instagram","loyalty-cards","map","photos","rss","soundcloud","twitter","vcard","video","web-page","youtube"],"monthly":"24","annual":"20","currency":"USD","featured":"0"}'
        ));

		\App\Model\Plan::create(array(
            'reseller_id' => 1,
            'name' => 'Professional',
            'sort' => 40,
            'settings' => '{"max_apps":"0","support":"-","domain":"1","download":"1","widgets":["about-us","call-us","catalogs","contact-us","content","coupons","e-commerce","email-us","events","facebook","flickr","forms","home-screen","instagram","loyalty-cards","map","photos","rss","soundcloud","twitter","vcard","video","web-page","youtube"],"monthly":"36","annual":"32","currency":"USD","featured":"0"}'
        ));

    }
}