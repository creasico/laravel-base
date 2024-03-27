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
        if (config('session.driver') === 'database' && ! Schema::hasTable(config('session.table'))) {
            Schema::create(config('session.table'), function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        if (config('queue.default') === 'database') {
            if (! Schema::hasTable(config('queue.connections.database.table'))) {
                Schema::create(config('queue.connections.database.table'), function (Blueprint $table) {
                    $table->id();
                    $table->string('queue')->index();
                    $table->longText('payload');
                    $table->unsignedTinyInteger('attempts');
                    $table->unsignedInteger('reserved_at')->nullable();
                    $table->unsignedInteger('available_at');
                    $table->unsignedInteger('created_at');
                });
            }

            if (! Schema::hasTable('job_batches')) {
                Schema::create('job_batches', function (Blueprint $table) {
                    $table->string('id')->primary();
                    $table->string('name');
                    $table->integer('total_jobs');
                    $table->integer('pending_jobs');
                    $table->integer('failed_jobs');
                    $table->text('failed_job_ids');
                    $table->mediumText('options')->nullable();
                    $table->integer('cancelled_at')->nullable();
                    $table->integer('created_at');
                    $table->integer('finished_at')->nullable();
                });
            }
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
        Schema::dropIfExists('notifications');

        if (config('queue.default') === 'database') {
            Schema::dropIfExists('job_batches');
            Schema::dropIfExists(config('queue.connections.database.table'));
        }

        if (config('session.driver') === 'database') {
            Schema::dropIfExists(config('session.table'));
        }
    }
};
