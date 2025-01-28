<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_addresses', function (Blueprint $table) {
            $table->id();
            // Foreign key references
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();

            // Address fields
            $table->string('address_type', 50)->nullable(); // e.g. HOME, WORK, BILLING, SHIPPING
            $table->string('line1', 255);
            $table->string('line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });

        Schema::table('contact_addresses', function (Blueprint $table) {
            // Optional index to speed up multi-tenant queries
            $table->index(['tenant_id', 'contact_id']);
        });
    }

    public function down(): void
    {
        Schema::table('contact_addresses', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'contact_id']);
        });

        Schema::dropIfExists('contact_addresses');
    }
};
