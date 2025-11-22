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
            $table->string('email')->unique();
            $table->string('position');
            $table->string('password');
            $table->string('status')->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('drivers', function (Blueprint $table) {
            $table->id('driver_id');
            $table->string('name');
            $table->string('license_number')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id('device_id');
            $table->string('device_name');
            $table->string('sim_number')->unique();
            $table->string('device_identifier')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('tricycles', function (Blueprint $table) {
            $table->id('tricycle_id');
            $table->string('plate_number')->unique();
            $table->string('motorcycle_model')->nullable();
            $table->string('color')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->foreign('driver_id')
                ->references('driver_id')
                ->on('drivers')
                ->onDelete('set null');

            $table->foreign('device_id')
                ->references('device_id')
                ->on('devices')
                ->onDelete('cascade');
        });

        Schema::create('device_coordinates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->foreign('device_id')
                ->references('device_id')
                ->on('devices')
                ->onDelete('cascade');
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

        Schema::create('tricycle_out_of_boundary_logs', function (Blueprint $table) {
            $table->id('log_id');

            $table->unsignedBigInteger('tricycle_id')->nullable();

            $table->unsignedBigInteger('driver_id')->nullable();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->string('status')->default('outside');
            $table->text('note')->nullable();

            $table->timestamp('detected_at')->useCurrent();

            $table->timestamps();

            $table->foreign('tricycle_id')
                ->references('tricycle_id')
                ->on('tricycles')
                ->onDelete('set null');

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
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('device_coordinates');
        Schema::dropIfExists('tricycles');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('tricycle_out_of_boundary_logs');
    }
};
