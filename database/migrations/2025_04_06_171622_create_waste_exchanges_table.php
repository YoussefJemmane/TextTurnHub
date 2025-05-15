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
        Schema::create('waste_exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('textile_waste_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_company_id')->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('receiver_company_id')->nullable()->constrained('company_profiles')->onDelete('cascade');
            $table->foreignId('receiver_artisan_id')->nullable()->constrained('artisan_profiles')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->enum('status', ['requested', 'accepted', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('exchange_date')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->json('exchange_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_exchanges');
    }
};
