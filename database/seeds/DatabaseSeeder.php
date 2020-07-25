<?php

use Illuminate\Database\Seeder;
use App\models\role;
use App\User;
use App\models\party;
use App\models\person;
use App\models\paper;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            $teacher=Role::create(['name'=>'Teacher','slug'=>'teacher','permissions'=>['create-paper'=>true,'read-paper'=>true,'submit-paper'=>'true']]);
            $student=Role::create(['name'=>'Student','slug'=>'student','permissions'=>['create-paper'=>true,'read-paper'=>true]]);
            $party=party::create(['partyId'=>'1','type'=>'person','owner'=>'1']);
            party::create(['partyId'=>'2','type'=>'person','owner'=>'1']);
            User::create(["name"=>'ali','email'=>'alim11@gmail.com','password'=>Hash::make('alim@11'),'partyId'=>1]);
            User::create(["name"=>'ali2','email'=>'alim12@gmail.com','password'=>Hash::make('alim@12'),'partyId'=>2]);
            person::create(["partyId"=>1,"firstName"=>"aliReza","lastName"=>"garivani","suffix"=>"Mr","degreeId"=>1,"gender"=>"M","birthDate"=>"2019-12-12"]);
            person::create(["partyId"=>2,"firstName"=>"mojtaba","lastName"=>"fazelinia","suffix"=>"Mr","degreeId"=>1,"gender"=>"M","birthDate"=>"2019-12-12"]);

    }
}
