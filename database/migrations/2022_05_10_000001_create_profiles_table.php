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
        Schema::create('identities', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('identity');

            $table->char('nik', 16)->unique()->nullable();
            $table->string('prefix', 10)->nullable();
            $table->string('fullname', 100);
            $table->string('suffix', 10)->nullable();
            $table->char('gender', 1);
            $table->date('birth_date')->nullable();
            $table->char('birth_place_code', 4)->nullable();
            $table->string('education', 3)->nullable();
            $table->unsignedTinyInteger('religion')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('photo_path')->nullable();
            $table->text('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('owner');

            $table->boolean('is_resident');
            $table->string('line');
            $table->char('rt', 3)->nullable();
            $table->char('rw', 3)->nullable();
            $table->char('village_code', 10)->nullable();
            $table->char('district_code', 6)->nullable();
            $table->char('regency_code', 4)->nullable();
            $table->char('province_code', 2)->nullable();
            $table->char('postal_code', 5)->nullable();
            $table->string('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('revision_id')->nullable()->constrained('documents')->nullOnDelete();

            $table->string('title')->nullable();
            $table->string('name');
            $table->string('path')->nullable();
            $table->string('drive')->nullable();
            $table->string('summary')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->foreignUuid('document_id')->constrained()->cascadeOnDelete();
            $table->nullableMorphs('attached_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('profiles');
    }
};
