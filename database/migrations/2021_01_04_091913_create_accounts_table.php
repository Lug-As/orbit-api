<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->id();
            $table->string('name', 25)->unique();
            $table->string('image', 100)->nullable();
            $table->string('telegram', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 10)->nullable();
            $table->unsignedInteger('followers')->default(0);
            $table->unsignedBigInteger('likes')->default(0);
            $table->foreignId('user_id')->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
