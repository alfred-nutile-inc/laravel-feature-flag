<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddTwitter extends Migration
{
    public function up()
    {
        /**
         * Nullable so if the area of the site that does not have this feature
         * set to ON will not be affected by inserts etc
         */
        Schema::table('users', function(Blueprint $table)
        {
            $table->string('twitter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table)
        {
            if(Schema::hasColumn('users', 'twitter'))
                $table->dropColumn('twitter');

        });
    }
}
