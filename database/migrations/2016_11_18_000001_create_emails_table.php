<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ipunkt\Laravel\EmailVerificationInterception\Models\EmailStatus;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->unsignedInteger('user_id');
            $table->enum('status', EmailStatus::validValues())
                ->default(EmailStatus::UNVERIFIED);
            $table->string('token')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('blacklisted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(function (Blueprint $table) {
            $table->dropForeign('emails_user_id_foreign');
        });
        Schema::dropIfExists('emails');
    }
}