<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quiz1 = Quiz::create([
            'title' => 'General Knowledge Quiz',
            'subject' => 'General Science & Trivia',
            'description' => 'Test your basic general knowledge and science trivia skills.',
            'duration_minutes' => 15,
            'status' => 'active',
        ]);

        $questions1 = [
            [
                'question' => 'What is the capital of India?',
                'options' => ['Mumbai', 'New Delhi', 'Kolkata', 'Chennai'],
                'correct_option' => 'New Delhi',
            ],
            [
                'question' => 'How many days are there in a week?',
                'options' => ['5', '6', '7', '8'],
                'correct_option' => '7',
            ],
            [
                'question' => 'Which planet is known as the Red Planet?',
                'options' => ['Earth', 'Mars', 'Jupiter', 'Venus'],
                'correct_option' => 'Mars',
            ],
            [
                'question' => 'What is 5 + 5?',
                'options' => ['8', '9', '10', '11'],
                'correct_option' => '10',
            ],
            [
                'question' => 'Which language is primarily used to structure web pages?',
                'options' => ['HTML', 'Python', 'Java', 'C++'],
                'correct_option' => 'HTML',
            ],
            [
                'question' => 'How many months are there in a year?',
                'options' => ['10', '11', '12', '13'],
                'correct_option' => '12',
            ],
            [
                'question' => 'Which element has the chemical symbol "O"?',
                'options' => ['Gold', 'Oxygen', 'Osmium', 'Silver'],
                'correct_option' => 'Oxygen',
            ],
            [
                'question' => 'What is the largest ocean on Earth?',
                'options' => ['Atlantic', 'Indian', 'Arctic', 'Pacific'],
                'correct_option' => 'Pacific',
            ],
            [
                'question' => 'Which animal is known as the King of the Jungle?',
                'options' => ['Tiger', 'Lion', 'Elephant', 'Leopard'],
                'correct_option' => 'Lion',
            ],
            [
                'question' => 'What is the boiling point of water at sea level?',
                'options' => ['90°C', '100°C', '110°C', '120°C'],
                'correct_option' => '100°C',
            ],
            [
                'question' => 'Which country is home to the Kangaroo?',
                'options' => ['South Africa', 'Australia', 'Brazil', 'India'],
                'correct_option' => 'Australia',
            ],
            [
                'question' => 'How many sides does a hexagon have?',
                'options' => ['5', '6', '7', '8'],
                'correct_option' => '6',
            ],
            [
                'question' => 'Which organ pumps blood throughout the human body?',
                'options' => ['Lungs', 'Brain', 'Heart', 'Liver'],
                'correct_option' => 'Heart',
            ],
            [
                'question' => 'What is the hardest natural substance on Earth?',
                'options' => ['Gold', 'Iron', 'Diamond', 'Platinum'],
                'correct_option' => 'Diamond',
            ],
            [
                'question' => 'Which gas do plants absorb from the atmosphere?',
                'options' => ['Oxygen', 'Carbon Dioxide', 'Nitrogen', 'Hydrogen'],
                'correct_option' => 'Carbon Dioxide',
            ],
            [
                'question' => 'How many primary colors are there?',
                'options' => ['2', '3', '4', '5'],
                'correct_option' => '3',
            ],
            [
                'question' => 'Which currency is used in the United Kingdom?',
                'options' => ['Euro', 'Dollar', 'Pound Sterling', 'Yen'],
                'correct_option' => 'Pound Sterling',
            ],
            [
                'question' => 'What is the largest continent by area?',
                'options' => ['Africa', 'North America', 'Asia', 'Europe'],
                'correct_option' => 'Asia',
            ],
            [
                'question' => 'Which instrument is used to measure temperature?',
                'options' => ['Barometer', 'Thermometer', 'Speedometer', 'Altimeter'],
                'correct_option' => 'Thermometer',
            ],
            [
                'question' => 'What is the main language spoken in Spain?',
                'options' => ['Spanish', 'French', 'Portuguese', 'Italian'],
                'correct_option' => 'Spanish',
            ],
        ];

        foreach ($questions1 as $q) {
            Question::create([
                'quiz_id' => $quiz1->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_option' => $q['correct_option'],
            ]);
        }

        $quiz2 = Quiz::create([
            'title' => 'Flutter & Mobile Dev Fundamentals',
            'subject' => 'Computer Science',
            'description' => 'Test your understanding of Flutter, Dart widgets, state management, and mobile engineering.',
            'duration_minutes' => 20,
            'status' => 'active',
        ]);

        $questions2 = [
            [
                'question' => 'Which programming language is used to build apps in Flutter?',
                'options' => ['Java', 'Swift', 'Dart', 'Kotlin'],
                'correct_option' => 'Dart',
            ],
            [
                'question' => 'Which company developed Flutter?',
                'options' => ['Apple', 'Microsoft', 'Google', 'Meta'],
                'correct_option' => 'Google',
            ],
            [
                'question' => 'What is the root widget in a standard Flutter app?',
                'options' => ['MaterialApp', 'Scaffold', 'Container', 'Column'],
                'correct_option' => 'MaterialApp',
            ],
            [
                'question' => 'Which widget provides a default layout structure with AppBar and Body?',
                'options' => ['Card', 'Scaffold', 'Stack', 'ListView'],
                'correct_option' => 'Scaffold',
            ],
            [
                'question' => 'What command is used to run Flutter static code analysis?',
                'options' => ['flutter run', 'flutter test', 'flutter analyze', 'flutter doctor'],
                'correct_option' => 'flutter analyze',
            ],
        ];

        foreach ($questions2 as $q) {
            Question::create([
                'quiz_id' => $quiz2->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_option' => $q['correct_option'],
            ]);
        }
    }
}
