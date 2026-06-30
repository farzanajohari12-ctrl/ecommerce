<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantValuesTable extends Migration
{
    public function up(): void
    {
        Schema::create('variant_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('variant_attribute_id')
                  ->constrained('variant_attributes')
                  ->onDelete('cascade');

            $table->string('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_values');
    }
}
