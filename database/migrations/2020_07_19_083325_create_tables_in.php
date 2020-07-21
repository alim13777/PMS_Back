<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesIn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("type");
            $table->mediumText("owner");
            $table->timestamps();
        });
        Schema::create('person', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("firstName");
            $table->mediumText("lastName");
            $table->mediumText("suffix");
            $table->mediumText("degreeId");
            $table->binary("gender");
            $table->timestamp("birthDate");
            $table->timestamps();
        });
        Schema::create('organization', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("name");
            $table->timestamps();
        });
        Schema::create('paper', function (Blueprint $table) {
            $table->id('paperId');
            $table->mediumText('title');
            $table->mediumText('type');
            $table->mediumText('comment');
            $table->mediumText('keyWords');
            $table->mediumText('topic');
            $table->timestamps();
        });
        Schema::create('paperParty', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("partyId");
            $table->bigInteger("paperId");
            $table->mediumText("relation");
            $table->timestamps();
        });
        schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("partyId");
            $table->bigInteger("school");
            $table->timestamp("degreeId");
            $table->timestamp("startDate");
            $table->timestamp("endDate");
            $table->timestamps();
        });
        Schema::create('paperState', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('paperId');
            $table->mediumText('state');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables_in');
    }
}
