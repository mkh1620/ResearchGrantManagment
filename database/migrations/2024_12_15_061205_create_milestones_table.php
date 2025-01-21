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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('target_completion_date');
            $table->text('deliverable');
            $table->enum('status', ['Pending', 'Completed']);
            $table->text('remark')->nullable();
            $table->timestamp('date_updated')->useCurrent();
            $table->foreignId('research_grant_id')->constrained('research_grants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};