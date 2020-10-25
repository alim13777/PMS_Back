<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('partyId');
            $table->mediumText("account");
            $table->mediumText("locale");
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('party', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("owner")->nullable();
            $table->mediumText("identity");
            $table->timestamps();
        });
        Schema::create('person', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("firstName");
            $table->mediumText("lastName");
            $table->mediumText("prefix")->nullable();
            $table->mediumText("degree")->nullable();
            $table->binary("gender");
            $table->timestamp("birthDate")->nullable();
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('organization', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("name");
            $table->mediumText("nameFa")->nullable();
            $table->mediumText("cityId")->nullable();
            $table->mediumText("type")->nullable();
            $table->timestamps();
            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('paper', function (Blueprint $table) {
            $table->id('paperId');
            $table->mediumText('title');
            $table->mediumText('type');
            $table->mediumText('description')->nullable();
            $table->mediumText('keywords')->nullable();
            $table->timestamps();
        });
        Schema::create('paper_party', function (Blueprint $table) {
            $table->id("id");
            $table->unsignedBigInteger("paperId");
            $table->unsignedBigInteger("partyId");
            $table->mediumText('localId')->nullable();
            $table->mediumText("role");
            $table->timestamp("startDate");
            $table->timestamp("endDate")->nullable();
            $table->timestamps();
            $table->unique("paperId","partyId");
            $table->foreign('paperId')->references('paperId')->on('paper')->onDelete('cascade');
            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');


        });
        schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("partyId");
            $table->bigInteger("school");
            $table->timestamp("degreeId");
            $table->timestamp("startDate");
            $table->timestamp("endDate")->nullable();
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('paperState', function (Blueprint $table) {
            $table->id("statusId");
            $table->unsignedBigInteger("paperPartyId");
            $table->mediumText('status');
            $table->timestamp('date');
            $table->timestamps();

            $table->foreign('paperPartyId')->references('id')->on('paper_party')->onDelete('cascade');
        });
        Schema::create('role', function (Blueprint $table) {
            $table->id('roleId');
            $table->string('name');
            $table->string('slug')->unique();
            $table->jsonb('permissions');
            $table->timestamps();
        });
        Schema::create('party_role', function (Blueprint $table) {
            $table->unsignedBigInteger('partyId');
            $table->unsignedBigInteger('roleId');
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
            $table->foreign('roleId')->references('roleId')->on('role')->onDelete('cascade');
        });
        Schema::create('journal',function(Blueprint $table){
            $table->id("partyId");
            $table->mediumText("issn")->nullable();
            $table->mediumText("impactFactor")->nullable();
            $table->timestamps();
            $table->foreign('partyId')->references('partyId')->on('organization')->onDelete('cascade');
        });
        Schema::create('contact',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger("partyId");
            $table->mediumText("type");
            $table->mediumText("value");
            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables');
    }
}
