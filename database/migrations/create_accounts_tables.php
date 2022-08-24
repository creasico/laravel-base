<?php

use Creasi\Laravel\Accounts\{Field, Metadata, Repository};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var Repository */
        $accounts = app(Repository::class);

        Schema::create('accounts', function (Blueprint $table) use ($accounts) {
            $table->id();
            $table->enum('type', $accounts->types())->nullable();
            $table->string('name');
            $table->string('display')->nullable();
            $table->string('slug')->nullable();
            $table->text('summary')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('accountables', function (Blueprint $table) {
            $table->foreignId('account_id');
            $table->foreignId('accountable_id');
            $table->foreignId('accountable_type');
            $table->string('cast')->nullable();
            $table->json('payload')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->cascadeOnDelete();
        });

        Schema::create('account_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable();
            $table->foreignId('related_id')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->foreign('related_id')->references('id')->on('accounts')->nullOnDelete();
        });

        Schema::create('account_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable();
            $table->enum('type', array_column(Field\Type::cases(), 'value'))->nullable();
            $table->string('key');
            $table->string('cast')->nullable();
            $table->json('payload')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_fields');
        Schema::dropIfExists('account_relations');
        Schema::dropIfExists('accountable');
        Schema::dropIfExists('accounts');
    }
};
