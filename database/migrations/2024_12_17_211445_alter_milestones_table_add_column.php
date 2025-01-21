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
        Schema::table('milestones', function (Blueprint $table) {
            // First drop the existing status column
            $table->dropColumn('status');
        });

        Schema::table('milestones', function (Blueprint $table) {
            // Add new status column with updated enum values
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('milestones', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('milestones', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Completed']);
        });
    }
};