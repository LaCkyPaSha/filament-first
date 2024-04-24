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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('role_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });


        $data =  array(
            [
                'name' => 'admin',
                'email' => 'admin@1',
                'password' => '1111',
                'role_id' => '1',
            ],
            [
                'name' => 'moder',
                'email' => 'moder@1',
                'password' => 'moder',
                'role_id' => '2',
            ],
            [
                'name' => 'bro',
                'email' => 'bro@1',
                'password' => 'bro',
                'role_id' => '3',
            ],
        );
        foreach ($data as $datum){
            $user = new \App\Models\User();
            $user->name = $datum['name'];
            $user->email = $datum['email'];
            $user->password = $datum['password'];
            $user->role_id = $datum['role_id'];
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
