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

        $feature = new \FriendsOfCat\LaravelFeatureFlags\FeatureFlag();
        $feature->key = 'add-twitter-field';
        $feature->variants = "on";
        $feature->save();

        $feature = new \FriendsOfCat\LaravelFeatureFlags\FeatureFlag();
        $feature->key = 'see-twitter-field';
        $feature->variants = "on";
        $feature->save();
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

            if($gate = \FriendsOfCat\LaravelFeatureFlags\FeatureFlag::where('key', 'see-twitter-field')->first())
            {
              $gate->delete();
            }

            if($gate = \FriendsOfCat\LaravelFeatureFlags\FeatureFlag::where('key', 'add-twitter-field')->first())
            {
              $gate->delete();
            }
        });
    }
}
