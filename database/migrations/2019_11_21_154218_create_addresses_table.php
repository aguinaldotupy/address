<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('addressable');
            $table->string('address_id')->nullable();
            $table->string('tag')->nullable();
            $table->string('address')->nullable();
            $table->integer('id_postal_code')->nullable();
            $table->string('zip_code');
            $table->string('port')->nullable();
            $table->string('complement')->nullable();
            $table->integer('parish_id')->nullable();
            $table->string('parish')->nullable();
            $table->string('district_id')->nullable();
            $table->string('district')->nullable();
            $table->string('county_id')->nullable();
            $table->string('county')->nullable();
            $table->string('locality_id')->nullable();
            $table->string('locality')->nullable();
            $table->string('country_id')->nullable();
            $table->string('country')->nullable();
            $table->text('observation')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
