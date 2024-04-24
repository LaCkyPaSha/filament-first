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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('completeness');
            $table->timestamps();
        });

        $data =  array(
            [
                'name' => 'Assigned',
                'completeness' => '0',
            ],
            [
                'name' => 'Approved',
                'completeness' => '1',
            ],
            [
                'name' => 'In Process',
                'completeness' => '2',
            ],
            [
                'name' => 'Is Waiting a review',
                'completeness' => '3',
            ],
            [
                'name' => 'Completed',
                'completeness' => '4',
            ],
        );
        foreach ($data as $datum){
            $status = new \App\Models\Status();
            $status->name = $datum['name'];
            $status->completeness = $datum['completeness'];
            $status->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
