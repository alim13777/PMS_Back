<?php

use Illuminate\Database\Seeder;
use App\models\role;
use App\User;
use App\models\party;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
            //$teacher=Role::create(['name'=>'Teacher','slug'=>'teacher','permissions'=>['create-paper'=>true,'read-paper'=>true,'submit-paper'=>'true']]);
            //$student=Role::create(['name'=>'Student','slug'=>'student','permissions'=>['create-paper'=>true,'read-paper'=>true]]);
            //$party=party::create(['partyId'=>'1','type'=>'person','owner'=>'1']);
            //User::create(["name"=>'ali','email'=>'alim11@gmail.com','password'=>Hash::make('alim@11'),'partyId'=>1]);
            \App\models\paper::create(['paperId'=>1,"title"=>"SCM","comment"=>"","type"=>"international","keywords"=>"","topic"=>"Insudtrial"]);

    }
}
