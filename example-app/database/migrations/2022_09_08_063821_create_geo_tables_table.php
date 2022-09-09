<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_tables', function (Blueprint $table) {
          $table->integer('geoname_id')->primary();
          $table->string('name',200);
          $table->string('ascii_name',200);
          $table->text('alternate_names',200);
          $table->decimal('longitude',8,5);
          $table->decimal('latitude',7,5);
          $table->char('feature_class',1);
          $table->string('featurecode',10);
          $table->char('country_code',2);
          $table->string('cc2',200);
          $table->string('admin1_code',20);
          $table->string('admin2_code',80);
          $table->string('admin3_code',20);
          $table->string('admin4_code',20);
          $table->decimal('populaion',11,0);
          $table->smallInteger('elevation');
          $table->smallInteger('dem');
          $table->string('timezone',40);
          $table->date('modification_date');


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
        Schema::dropIfExists('geo_tables');
    }
};
