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
        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('committee_name');
            $table->string('committee_code');
            $table->string('committee_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('no_of_members');
            $table->string('draw_frequency');
            $table->decimal('payment_amount');
            $table->enum('payment_cycle', ['weekly','monthly']);
            $table->string('payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committees');
    }
};
