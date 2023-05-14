<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('display')->nullable();
            $table->string('slug')->nullable();
            $table->text('summary')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('accountables', function (Blueprint $table) {
            $table->foreignId('account_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->nullableMorphs('accountable');
        });

        Schema::create('account_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->foreignId('connected_id')->references('id')->on('accounts')->nullOnDelete();
            $table->text('notes')->nullable();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('account_connections');
        Schema::dropIfExists('accountable');
        Schema::dropIfExists('accounts');
    }
};
