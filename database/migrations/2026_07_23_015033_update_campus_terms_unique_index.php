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
        Schema::table('campus_terms', function (Blueprint $table) {
            $table->dropUnique('campus_terms_campus_id_unique');
            $table->unique(['campus_id', 'tenant_id'], 'campus_terms_campus_tenant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campus_terms', function (Blueprint $table) {
            $table->dropUnique('campus_terms_campus_tenant_unique');
            $table->unique('campus_id');
        });
    }
};
