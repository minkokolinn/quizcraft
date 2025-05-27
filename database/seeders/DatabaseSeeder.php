<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Type;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        User::create([
            "name" => "Dr Thiha",
            "email" => "sample@gmail.com",
            "password" => "sample123",
            "subject" => "Biology",
            "grade" => "10,11,12",
            "chapter" => "1,2,3,4,5,6"
        ]);

        Type::create([
                "name" => "True False",
                "header" => "State TRUE or FALSE to the following statements. Do not copy the statements.",
                "mark" => 1
        ]);
        Type::create([
            "name" => "Completion",
            "header" => "Complete the following statements with appropriate words. Do not copy the statements.",
            "mark" => 1
        ]);
        Type::create([
            "name" => "Multiple Choice",
            "header" => "Choose the correct answer for the following statements. Do not copy the statements.",
            "mark" => 1
        ]);
        Type::create([
            "name" => "Short Question",
            "header" => "Answer ALL questions.",
            "mark" => 5
        ]); 
        Type::create([
            "name" => "Long Question",
            "header" => "Answer ANY FOUR questions.",
            "mark" => 10
        ]);

        // $types = Type::all();
        // foreach ($types as $type) {
        //     for ($i = 1; $i <= 30; $i++) {
        //         // $grade = fake()->numberBetween(10,12);
        //         $grade = 10;
        //         // $chapter = $faker->numberBetween(1, 6);
        //         $chapter = 1;
        //         $body = '';

        //         switch ($type->id) {
        //             case 1: // True/False
        //             case 4: // Short Question
        //             case 5: // Long Question
        //                 $body = $faker->sentence();
        //                 break;
        //             case 2: // Completion
        //                 $sentence = $faker->sentence();
        //                 $words = explode(' ', $sentence);
        //                 $blankIndex = rand(2, count($words) - 2);
        //                 $words[$blankIndex] = '------';
        //                 $body = implode(' ', $words);
        //                 break;

        //             case 3: // Multiple Choice
        //                 $body = $faker->sentence();
        //                 $body .= " (A) ".Str::random(10);
        //                 $body .= " (B) ".Str::random(10);
        //                 $body .= " (C) ".Str::random(10);
        //                 $body .= " (D) ".Str::random(10);
        //                 break;
        //         }

        //         $question=Question::create([
        //             "no"=>$i,
        //             "body"=>$body,
        //             "image"=>null,
        //             "grade"=>$grade,
        //             "chapter"=>$chapter,
        //             "type_id"=>$type->id
        //         ]);
        //     }
        // }
    }
}
