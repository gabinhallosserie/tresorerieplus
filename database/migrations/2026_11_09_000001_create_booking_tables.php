<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('slot_minutes')->default(60);
            $table->unsignedSmallInteger('buffer_minutes')->default(15);
            $table->unsignedSmallInteger('min_notice_hours')->default(24);
            $table->unsignedSmallInteger('max_days_ahead')->default(45);
            $table->timestamps();
        });

        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('weekday');
            $table->time('starts_at');
            $table->time('ends_at');
            $table->timestamps();
        });

        Schema::create('closed_periods', function (Blueprint $table) {
            $table->id();
            $table->date('starts_on');
            $table->date('ends_on');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->dateTime('active_slot')->nullable()->unique();
            $table->string('status', 20);
            $table->string('mode', 20);
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('company')->nullable();
            $table->string('activity', 40);
            $table->text('message')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('token', 64)->unique();
            $table->timestamp('reminder_sent_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('starts_at');
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('subject', 40);
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('closed_periods');
        Schema::dropIfExists('opening_hours');
        Schema::dropIfExists('booking_settings');
    }
};
