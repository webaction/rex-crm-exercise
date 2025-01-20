<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->timestamps();

            $table->unique(['tenant_id', 'phone'], 'tenant_phone_unique');
            $table->unique(['tenant_id', 'email'], 'tenant_email_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
