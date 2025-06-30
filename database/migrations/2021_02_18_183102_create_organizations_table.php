<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('org_name');
            $table->longText('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('gsm')->nullable();
            $table->string('phone_no')->nullable();
            $table->integer('rating', '0');
            $table->string('contract');
            $table->integer('contract_frequency', '0');
            $table->dateTime('contract_start_date', '0');
            $table->dateTime('contract_end_date', '0');
            $table->integer('price', '0');
            $table->integer('transport_price', '0');
            $table->string('picture')->nullable();
            $table->unsignedBigInteger('add_by', '0');
            $table->string('add_ip', '0');
            $table->unsignedBigInteger('update_by', '0');
            $table->string('update_ip', '0');
            $table->engine = 'InnoDB';
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
        Schema::dropIfExists('organizations');
    }
}
