<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('up_or_down')->comment('1 for up and 0 for down');
            $table->string('project_name');
            $table->string('project_url');
            $table->string('project_logo_path');
            $table->integer('google_rank');
            $table->bigInteger('time');
            $table->integer('total_update');
            $table->integer('is_backup_active')->comment('1 for active and 0 for inactive');
            $table->integer('total_site_helth');
            $table->integer('total_php_issue');
            $table->string('wp_admin_url');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_user_id_foreign');
        });
        Schema::dropIfExists('projects');
    }
};