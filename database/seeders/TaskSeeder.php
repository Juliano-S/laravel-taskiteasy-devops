<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    private $data = [
        [
            "title" => "Secure Network Protocols",
            "description" => "<p>Develop and implement <i>secure network protocols </i>for data communication. Emphasis on encryption, authentication, and efficient data transfer. Knowledge of cryptography is essential.</p><p><strong>Prerequisites:</strong> Networking and cryptography fundamentals.</p><p><i>Duration:</i> 4 months</p>"
        ],
        [
            "title" => "High-Performance Computing",
            "description" => "<p>This project involves optimizing code for high-performance computing environments. Topics include parallel programming, GPU acceleration, and optimizing algorithms for speed.</p><p><strong>Skills:</strong> Parallel programming, CUDA, OpenMP.</p><p><i>Duration:</i> 6 months</p>"
        ],
        [
            "title" => "Machine Learning Infrastructure",
            "description" => "<p>Build a robust machine learning infrastructure capable of handling large datasets. Focus on scalability, model deployment, and efficient data processing.</p><p><strong>Requirements:</strong> Proficiency in machine learning and distributed systems.</p><p><i>Duration:</i> 5 months</p>"
        ],
        [
            "title" => "Web Application Security",
            "description" => "<p>Explore and implement strategies for securing web applications. Topics include input validation, session management, and protection against common security vulnerabilities.</p><p><strong>Prerequisites:</strong> Understanding of web development and security principles.</p><p><i>Duration:</i> 3 months</p>"
        ],
        [
            "title" => "Blockchain Development",
            "description" => "<p>Develop decentralized applications (DApps) using blockchain technology. Learn about smart contracts, consensus algorithms, and build applications on popular blockchain platforms.</p><p><strong>Skills:</strong> Solid understanding of blockchain concepts and programming skills.</p><p><i>Duration:</i> 4 months</p>"
        ],
        [
            "title" => "Data Science for Finance",
            "description" => "<p>Apply data science techniques to analyze financial data. Explore predictive modeling, risk assessment, and data-driven decision-making in the context of finance.</p><p><strong>Requirements:</strong> Basic knowledge of finance and data analysis.</p><p><i>Duration:</i> 6 months</p>"
        ],
        [
            "title" => "Cloud Native Application Development",
            "description" => "<p>Design and build cloud-native applications using microservices architecture. Explore containerization, orchestration, and cloud-native development best practices.</p><p><strong>Skills:</strong> Familiarity with cloud platforms and microservices.</p><p><i>Duration:</i> 5 months</p>"
        ],
        [
            "title" => "Cybersecurity Incident Response",
            "description" => "<p>Develop and implement a robust incident response plan for cybersecurity incidents. Learn about identifying, containing, and mitigating security breaches.</p><p><strong>Prerequisites:</strong> Knowledge of cybersecurity fundamentals.</p><p><i>Duration:</i> 4 months</p>"
        ],
        [
            "title" => "Do Laravel related things",
            "description" => "<p>All my Laravel tasks</p>",
            "children" => [
                [
                    'title' => 'Write a love letter to Laravel',
                    'description' => 'Compose a heartfelt love letter to Laravel, explaining how it has revolutionized your coding life. Express your deep affection for eloquent relationships and artisan commands.',
                    'priority' => 3,
                    'state' => 'new',
                    'time_estimated'=> 15,
                    'time_spent' => 0,
                    'completed_at' => NULL
                ],
                [
                    'title' => 'Have a debate with a coding error',
                    'description' => 'Engage in a passionate debate with a coding error in your Laravel project. Argue your case for why it should work, and listen to its "arguments" for why it will not.',
                    'priority' => 2,
                    'state' => 'completed',
                    'time_estimated'=> 10,
                    'time_spent' => 35,
                    'completed_at' => '2023-09-20 14:50:00'
                ],
                [
                    'title' => 'Conduct a Laravel Bake-Off',
                    'description' => 'Challenge yourself to a Laravel Bake-Off where you create different Laravel projects with unusual names like "CakeBoss" and "CookieMonsta." See which one turns out the most delicious.',
                    'priority' => 4,
                    'state' => 'in progress',
                    'time_estimated'=> 30,
                    'time_spent' => 4,
                    'completed_at' => NULL
                ],
                [
                    'title' => 'Create a Migration for your sock drawer',
                    'description' => 'Generate a Laravel migration to organize and maintain your sock drawer. Make sure your sock "schema" is well-structured and relationships between socks are eloquent.',
                    'priority' => 1,
                    'state' => 'completed',
                    'time_estimated'=> 5,
                    'time_spent' => 10,
                    'completed_at' => '2023-07-10 17:55:00'
                ],
                [
                    'title' => 'Build a Laravel Artisan shrine',
                    'description' => 'Construct a small shrine to the Laravel Artisan command in your workspace. Pray to it daily for quick and bug-free migrations and seedings.',
                    'priority' => 2,
                    'state' => 'in progress',
                    'time_estimated'=> 45,
                    'time_spent' => 37,
                    'completed_at' => NULL
                ],
                [
                    'title' => 'Laravel Task Scheduler Challenge',
                    'description' => 'Configure a task scheduler to automatically water your real plants, name them "Lara," and have them thank you in a log file.',
                    'priority' => 3,
                    'state' => 'on hold',
                    'time_estimated'=> 20,
                    'time_spent' => 0,
                    'completed_at' => NULL
                ]
            ]
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->data as $item) {
            $children = [];
            if (isset($item['children'])) {
                $children = $item['children'];
                // Remove children to make it acceptable for mass assignment
                unset($item['children']);
            }

            $task = Task::create($item);
            foreach ($children as $child) {
                $task->children()->create($child);
            }
        }
    }
}
