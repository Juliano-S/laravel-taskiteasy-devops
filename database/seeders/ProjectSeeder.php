<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    private $data = [
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->data as $item) {
            Project::create($item);
        }
    }
}
