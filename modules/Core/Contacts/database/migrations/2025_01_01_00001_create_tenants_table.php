<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();  // big integer AI primary key
            $table->string('name', 100);
            $table->string('domain', 255)->nullable();
            // Additional tenant-level fields as needed:
            // e.g. billing info, contact email, etc.

            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
