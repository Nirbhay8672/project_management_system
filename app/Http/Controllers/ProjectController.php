<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('project/Index');
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            $search = $request->search;
            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;

            $query = Project::query();

            if ($search) {
                $query->where('project_name', 'like', '%' . $search . '%');
            }

            $total = $query->count();
            $offset = ($page - 1) * $perPage;

            $projects = $query->offset($offset)
                ->limit($perPage)
                ->get();

            $total_pages = ceil($total / $perPage);

            $startIndex = ($page - 1) * $perPage;
            $endIndex = min($startIndex + $perPage, $total);

            return $this->successResponse(message: "Project details.", data: [
                'projects' => $projects,
                'total' => $total,
                'total_pages' => $total_pages,
                'start_index' => $startIndex + 1,
                'end_index' => $endIndex,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function addWebsite(Request $request): JsonResponse
    {
        dd($request->all());

        try {
            DB::beginTransaction();

            // $project = new Project();

            // $project->fill([
            //     'user_id' => 1,
            //     'up_or_down' => rand(0, 1),
            //     'project_name' => 'Project',
            //     'project_url' => 'http://project_management_system.test/',
            //     'project_logo_path' => '',
            //     'google_rank' => rand(1, 95),
            //     'time' => 2400,
            //     'total_update' => rand(5, 10),
            //     'is_backup_active' => rand(0, 1),
            //     'total_site_helth' => rand(1, 5),
            //     'total_php_issue' => rand(2, 20),
            //     'wp_admin_url' => 'https://wordpress.org/documentation/'
            // ])->save();

            // $this->storeFile($project);

            DB::commit();

            return $this->successResponse(message: "Website has been added successfully.");
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(message: $exception->getMessage());
        }
    }
}