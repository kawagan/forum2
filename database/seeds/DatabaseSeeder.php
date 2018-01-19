<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::statement('SET foreign_key_checks=0');

        App\User::truncate();
        App\Thread::truncate();
        App\Reply::truncate();
        App\ThreadSubscription::truncate();
        
        $quantityUser=10;
        $quantityThread=50;
        $quantityReply=300;
        $quantityChennl=5;

        factory(App\User::class,$quantityUser)->create();
        factory(App\Channel::class,$quantityChennl)->create();
        factory(App\Thread::class,$quantityThread)->create();
        factory(App\Reply::class,$quantityReply)->create();


    }
}
