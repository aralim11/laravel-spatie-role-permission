<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('blog')->insert([
            ['category_id' => '1', 'title' => "Tamim’s revival or Miraz controversy?", 'description' => "The Chattogram-leg began in the best possible way as Tamim cancelled out Lendl Simmons' hundred with a magnificent unbeaten 111, the left-hander's second BPL ton, to help Minister Group Dhaka register a comprehensive nine-wicket win against Sylhet Sunrisers on the opening day's second match.", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['category_id' => '2', 'title' => "Brazil cruise past Paraguay in comfortable 4-0 win", 'description' => "The defeat ended Paraguay's hopes of qualifying for Qatar and further cemented Brazil's position as one of the favourites to lift the trophy in December. Brazil top the South American qualifying group with no defeats in 15 games and the win extended to 61 matches their unbeaten home record in World Cup qualifiers.", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['category_id' => '3', 'title' => "In-form Moeen arrives to boost Comilla Victorians", 'description' => "After completing his latest national assignment, England all-rounder Moeen Ali reached Dhaka today and joined his Bangladesh Premier League (BPL) franchise Comilla Victorians, confirmed the team management.", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],

            ['category_id' => '3', 'title' => "Fletcher, Soumya carnage sees Khulna pick up easy win", 'description' => "Sent to bat, Sylhet lost both openers cheaply before Mithun's lone effort saw them to a challenging score. However, Khulna's Andre Fletcher and Soumya Sarkar made easy work of the chase. Soumya made his first meaningful contribution with the bat in the tournament, hitting a 31-ball 43 during a 99-run opening stand with Fletcher, who remained unbeaten on a 47-ball 71.", 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")],
        ]);
    }
}
