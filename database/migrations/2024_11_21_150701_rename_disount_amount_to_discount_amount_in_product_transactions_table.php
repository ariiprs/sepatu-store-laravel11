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
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->renameColumn('disount_amount', 'discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->renameColumn('discount_amount', 'disount_amount');
        });
    }
};
