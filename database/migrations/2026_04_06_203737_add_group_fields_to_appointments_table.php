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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('google_client_event_id');
            $table->string('preferred_contact')->nullable()->after('phone');
            $table->text('notes')->nullable()->after('preferred_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['group_id', 'preferred_contact', 'notes']);
        });
    }
};
