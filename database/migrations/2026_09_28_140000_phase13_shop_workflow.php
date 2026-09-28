<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['category_id', 'name']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('subcategory_id')->nullable()->after('category_id')->constrained('subcategories')->nullOnDelete();
            $table->string('ram', 40)->nullable()->after('notes');
            $table->string('storage', 40)->nullable();
            $table->string('color', 40)->nullable();
            $table->string('pta_status', 30)->nullable();
            $table->string('network_status', 30)->nullable();
            $table->string('condition', 20)->nullable();
            $table->string('warranty_type', 30)->nullable();
            $table->string('imei_mode', 20)->default('single');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->string('acquisition_source', 20)->default('SUPPLIER')->after('status');
            $table->string('used_status', 20)->nullable();
            $table->foreignId('seller_customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('condition', 20)->nullable();
            $table->text('testing_notes')->nullable();
            $table->string('accessories_received', 190)->nullable();
            $table->decimal('repair_cost', 12, 2)->default(0);
            $table->decimal('asking_price', 12, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('seller_customer_id');
            $table->dropColumn([
                'acquisition_source', 'used_status', 'condition', 'testing_notes',
                'accessories_received', 'repair_cost', 'asking_price',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subcategory_id');
            $table->dropColumn([
                'ram', 'storage', 'color', 'pta_status', 'network_status',
                'condition', 'warranty_type', 'imei_mode',
            ]);
        });

        Schema::dropIfExists('subcategories');
    }
};
