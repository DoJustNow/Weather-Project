<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameSocialiteComunsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('github_id','github');
            $table->renameColumn('mailru_id','mailru');
            $table->renameColumn('google_id','google');
            $table->renameColumn('vkontakte_id','vkontakte');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('github', 'github_id');
            $table->renameColumn('mailru', 'mailru_id');
            $table->renameColumn('google', 'google_id');
            $table->renameColumn('vkontakte', 'vkontakte_id');
        });
    }
}
