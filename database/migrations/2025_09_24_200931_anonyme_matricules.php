<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('anonymized_matricules', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->unsignedBigInteger('anonymized_id');
            $table->timestamps();
            
            $table->index('matricule');
            $table->index('anonymized_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anonymized_matricules');
    }

};
