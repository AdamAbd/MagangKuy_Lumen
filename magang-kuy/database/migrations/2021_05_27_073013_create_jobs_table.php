<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('category');
            $table->string('company_name');
            $table->string('company_logo')->default('storage/default/logo_shollu.png');
            $table->string('location');
            $table->string('salary');
            $table->text('description');
            $table->string('qualifications')->nullable();
            $table->string('responsibilities')->nullable();

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
        Schema::dropIfExists('jobs');
    }
}
