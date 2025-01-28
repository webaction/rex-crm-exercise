<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            // Foreign key reference to 'tenants' table
            $table->foreignId('tenant_id')
                ->constrained('tenants')
                ->cascadeOnDelete();

            // Basic contact info
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('salutation', 50)->nullable();    // e.g. Mr, Ms, Dr
            $table->string('suffix', 50)->nullable();        // e.g. Jr, Sr, III
            $table->string('preferred_name', 100)->nullable();
            $table->string('job_title', 100)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('contact_type', 50)->nullable();
            $table->string('status', 50)->nullable();

            $table->timestamps();

            $table->foreignId('owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        // Optional index for frequent queries by tenant
        Schema::table('contacts', function (Blueprint $table) {
            $table->index(['tenant_id', 'last_name']);
        });
    }

    public function down(): void
    {

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'last_name']);

            $table->dropForeign(['owner_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->dropColumn(['owner_id', 'created_by', 'updated_by']);
        });

        Schema::dropIfExists('contacts');
    }
};
