<?php

use Illuminate\Database\Seeder;
use App\models\role;
use App\User;
use App\models\party;
use App\models\person;
use App\models\paper;
use App\models\organization;
use App\models\journal;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


            party::create(['partyId'=>1,'identity'=>'person','owner'=>'1']);
            party::create(['partyId'=>2,'identity'=>'Organization','owner'=>'1']);
            User::create(['email'=>'alim11@gmail.com',"account"=>"permiun","email_verified_at"=>"2019-12-12",'partyId'=>1,"locale"=>"fa",'password'=>Hash::make('alim@110')]);
            person::create(["partyId"=>1,"firstName"=>"aliReza","lastName"=>"garivani","prefix"=>"Mr","degree"=>"1","gender"=>"M","birthDate"=>"2019-12-12"]);
            organization::create(["partyId"=>2,"name"=>"ISI","nameFa"=>"ٓژورنال","cityId"=>"1","type"=>"journal"]);
            journal::create(["partyId"=>2,"issn"=>"1","impactFactor"=>"1"]);

    }
}
