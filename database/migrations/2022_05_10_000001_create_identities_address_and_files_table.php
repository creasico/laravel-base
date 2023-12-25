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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token');
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('identity');

            $table->char('nik', 16)->unique()->nullable();
            $table->string('prefix', 20)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->char('birth_place_code', 4)->nullable();
            $table->string('education', 3)->nullable();
            $table->unsignedTinyInteger('religion')->nullable();
            $table->unsignedSmallInteger('tax_status')->nullable();
            $table->string('tax_id', 16)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('addressable');

            $table->unsignedSmallInteger('type')->nullable();
            $table->string('line');
            $table->char('rt', 3)->nullable();
            $table->char('rw', 3)->nullable();
            $table->char('village_code', 10)->nullable();
            $table->char('district_code', 6)->nullable();
            $table->char('regency_code', 4)->nullable();
            $table->char('province_code', 2)->nullable();
            $table->char('postal_code', 5)->nullable();
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('revision_id')->nullable();

            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('path');
            $table->string('disk')->nullable();
            $table->string('summary', 200)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('file_uploads', function (Blueprint $table) {
            $table->foreign('revision_id')->references('id')->on('file_uploads')->nullOnDelete();
        });

        Schema::create('file_attached', function (Blueprint $table) {
            $table->foreignUuid('file_upload_id')->constrained('file_uploads')->cascadeOnDelete();
            $table->nullableMorphs('attachable');
            $table->unsignedSmallInteger('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_attached');
        Schema::dropIfExists('file_uploads');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('profiles');
    }
};
