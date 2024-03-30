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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name', 150);
            $table->string('alias', 50)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('organizations_relatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('stakeholder');

            $table->string('code')->unique()->nullable();
            $table->boolean('is_primary')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->unsignedSmallInteger('status')->nullable();
            $table->unsignedSmallInteger('personnel_status')->default(0);
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
        });

        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
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
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('people_relatives', function (Blueprint $table) {
            $table->foreignId('person_id')->constrained()->cascadeOnDelete();
            $table->foreignId('relative_id')->constrained('people')->cascadeOnDelete();

            $table->unsignedSmallInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people_relatives');
        Schema::dropIfExists('people');
        Schema::dropIfExists('organizations_relatives');
        Schema::dropIfExists('organizations');
    }
};
