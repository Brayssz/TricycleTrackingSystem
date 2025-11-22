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
        Schema::create('tricycle_maintenance_logs', function (Blueprint $table) {
            $table->id('maintenance_id');
            $table->unsignedBigInteger('tricycle_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->string('maintenance_type'); // e.g., Engine, Tire, etc.
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->default(0.00);
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();

            $table->foreign('tricycle_id')
                ->references('tricycle_id')
                ->on('tricycles')
                ->onDelete('cascade');

            $table->foreign('driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tricycle_maintenance_logs');
    }
};
