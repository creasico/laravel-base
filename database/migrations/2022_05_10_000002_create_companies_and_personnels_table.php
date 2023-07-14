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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('logo_path')->nullable();
            $table->text('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('company_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('stakeholder');

            $table->unsignedSmallInteger('type')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->text('remark')->nullable();
        });

        Schema::create('personnels', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('photo_path')->nullable();
            $table->text('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('personnel_relatives', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('personnels')->cascadeOnDelete();

            $table->unsignedSmallInteger('status')->nullable();
            $table->text('remark')->nullable();
        });

        Schema::create('employments', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('personnels')->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);
            $table->unsignedSmallInteger('type')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('company_relatives');
        Schema::dropIfExists('companies');
    }
};
