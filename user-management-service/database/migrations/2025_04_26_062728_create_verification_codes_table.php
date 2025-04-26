<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCodesTable extends Migration
{
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('user_id'); 
            $table->uuid('device_id'); 
            $table->string('code', 10);
            $table->enum('status', ['PENDING', 'USED', 'EXPIRED']); 
            $table->integer('attempt_count')->default(0);
            $table->timestamp('sent_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps(0); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
}
