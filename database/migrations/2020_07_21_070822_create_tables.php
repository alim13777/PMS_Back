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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('partyId');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
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

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('organization', function (Blueprint $table) {
            $table->id("partyId");
            $table->mediumText("name");
            $table->mediumText("type");
            $table->timestamps();
            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
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
        Schema::create('paper_party', function (Blueprint $table) {
            $table->unsignedBigInteger("paperId");
            $table->unsignedBigInteger("partyId");
            $table->mediumText("relation");
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
            $table->timestamp("endDate");
            $table->timestamps();

            $table->foreign('partyId')->references('partyId')->on('party')->onDelete('cascade');
        });
        Schema::create('paperState', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paperId');
            $table->mediumText('state');
            $table->timestamps();

            $table->foreign('paperId')->references('paperId')->on('paper')->onDelete('cascade');
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
        Schema::create('publisher', function (Blueprint $table) {
            $table->id('partyId');
            $table->timestamps();
            $table->foreign('partyId')->references("partyId")->on("organization")->onDelete('cascade');
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
