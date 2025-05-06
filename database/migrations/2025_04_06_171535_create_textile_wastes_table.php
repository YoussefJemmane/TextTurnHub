<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('textile_wastes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_profiles_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('waste_type', ['fabric', 'yarn', 'offcuts', 'scraps', 'other']);
            $table->string('material_type');
            $table->decimal('quantity', 10, 2);
            $table->enum('unit', ['kg', 'meters', 'pieces']);
            $table->string('condition');
            $table->string('color')->nullable();
            $table->string('composition')->nullable();
            $table->decimal('minimum_order_quantity', 10, 2)->nullable();
            $table->decimal('price_per_unit', 10, 2)->nullable();
            $table->string('location');
            $table->enum('availability_status', ['available', 'pending', 'exchanged'])->default('available');
            $table->string('images')->nullable();
            $table->json('sustainability_metrics')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textile_wastes');
    }
};
