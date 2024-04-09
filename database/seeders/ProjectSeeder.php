<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::truncate();
        Storage::deleteDirectory('/public/projects');

        for ($i = 1; $i <= 52; $i++) {

            $project = new Project();

            $project->fill([
                'user_id' => 1,
                'up_or_down' => rand(0, 1),
                'project_name' => 'Project ' . $i,
                'project_url' => 'http://project_management_system.test/',
                'project_logo_path' => '',
                'google_rank' => rand(1, 95),
                'time' => 2400,
                'total_update' => rand(5, 10),
                'is_backup_active' => rand(0, 1),
                'total_site_helth' => rand(1, 5),
                'total_php_issue' => rand(2, 20),
                'wp_admin_url' => 'https://wordpress.org/documentation/'
            ])->save();

            $this->storeFile($project);
        }

    }

    private function storeFile(Project $project)
    {
        $files = File::files(public_path('images'));
        $file = null;

        foreach ($files as $value) {
            if ($value->getFilename() === 'wordpress.png') {
                $file = $value;
            }
        }

        if (!is_null($file)) {
            $path = 'project_' . time() . (string) random_int(0, 5) . '.' . $file->getExtension();

            Storage::disk('public')->put('projects/' . $project->id . '/' . $path, $file->getContents());

            $project->fill([
                'project_logo_path' => 'projects/' . $project->id . '/' . $path,
            ])->save();
        }
    }
}