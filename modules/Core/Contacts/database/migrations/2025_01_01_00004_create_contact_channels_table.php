<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contact_channels', function (Blueprint $table) {
            $table->id();
            // Foreign key references
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('contacts')->cascadeOnDelete();

            // Communication fields
            $table->string('channel_type', 50)->nullable(); // e.g. PHONE, EMAIL, SOCIAL
            $table->string('value', 255);
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
        });

        Schema::table('contact_channels', function (Blueprint $table) {
            // Optional index to speed up multi-tenant queries
            $table->index(['tenant_id', 'contact_id']);
        });
    }

    public function down(): void
    {
        Schema::table('contact_channels', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'contact_id']);
        });

        Schema::dropIfExists('contact_channels');
    }
};
