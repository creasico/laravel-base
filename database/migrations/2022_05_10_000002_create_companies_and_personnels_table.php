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

            $table->string('code')->unique()->nullable();
            $table->boolean('is_primary')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->unsignedSmallInteger('employment_status')->default(0);
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
        });

        Schema::create('personnels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name', 150);
            $table->string('prefix', 20)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->string('alias', 50)->nullable();
            $table->char('nik', 16)->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->char('birth_place_code', 4)->nullable();
            $table->char('gender', 1);
            $table->string('education', 3)->nullable();
            $table->unsignedTinyInteger('religion')->nullable();
            $table->unsignedSmallInteger('tax_status')->nullable();
            $table->string('tax_id', 16)->nullable();
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('personnel_relatives', function (Blueprint $table) {
            $table->foreignId('personnel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('personnels')->cascadeOnDelete();

            $table->unsignedSmallInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_relatives');
        Schema::dropIfExists('personnels');
        Schema::dropIfExists('business_relatives');
        Schema::dropIfExists('businesses');
    }
};
