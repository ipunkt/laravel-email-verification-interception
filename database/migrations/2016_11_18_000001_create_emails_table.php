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
            $table->unsignedBigInteger('user_id');
            $table->enum('status', EmailStatus::validValues())
                ->default(EmailStatus::UNVERIFIED);
            $table->string('token')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('blacklisted_at')->nullable();
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
        Schema::dropIfExists('emails');
    }
}