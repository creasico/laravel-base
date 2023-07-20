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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('alias', 50)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->unsignedSmallInteger('tax_status')->nullable();
            $table->string('tax_id', 16)->nullable();
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('business_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('stakeholder');

            $table->boolean('is_internal')->default(false);
            $table->string('code')->unique()->nullable();
            $table->unsignedSmallInteger('type')->nullable();
        });

        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name', 150);
            $table->string('alias', 50)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->char('gender', 1);
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('personnel_relatives', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('personnels')->cascadeOnDelete();

            $table->unsignedSmallInteger('status')->nullable();
        });

        Schema::create('employments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained('businesses')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('personnels')->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);
            $table->string('code')->unique()->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employments');
        Schema::dropIfExists('personnel_relatives');
        Schema::dropIfExists('personnels');
        Schema::dropIfExists('business_relatives');
        Schema::dropIfExists('business');
    }
};
