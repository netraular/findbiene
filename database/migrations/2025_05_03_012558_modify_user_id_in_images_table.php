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
        Schema::table('images', function (Blueprint $table) {
            // Drop foreign key first if it exists and you want to keep the column simple
            // Note: The default foreign key name might vary based on your DB/Laravel version
            // Check your DB schema if unsure. Common pattern: images_user_id_foreign
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Ignore if foreign key doesn't exist or has a different name
                \Illuminate\Support\Facades\Log::warning('Could not drop foreign key user_id: ' . $e->getMessage());
            }
            // Modify column to be nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
            // Optionally re-add constraint allowing nulls or making it cascade on delete differently
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
             // Revert back - Be careful here, might fail if there are NULL values
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            // Optionally re-add original constraint if you removed it
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};