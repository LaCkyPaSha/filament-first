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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $data =  array(
            [
                'name' => 'ADMIN',
            ],
            [
                'name' => 'MODER',
            ],
            [
                'name' => 'USER',
            ],
        );
        foreach ($data as $datum){
            $role = new \App\Models\Role();
            $role->name = $datum['name'];
            $role->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
