<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                ->orderBy('id', 'DESC')
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

    public function addWebsite(ProjectFormRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $project = new Project();

            $project->fill([
                'user_id' => Auth::user() ? Auth::user()->id : 1,
                'up_or_down' => $request->up_or_down,
                'project_name' => $request->project_name,
                'project_url' => $request->project_url,
                'project_logo_path' => '',
                'google_rank' => $request->google_rank,
                'time' => $request->google_rank,
                'total_update' => $request->total_update,
                'is_backup_active' => $request->is_backup_active,
                'total_site_helth' => $request->total_site_helth,
                'total_php_issue' => $request->total_php_issue,
                'wp_admin_url' => $request->wp_admin_url,
            ])->save();

            if ($request->project_logo) {
                $this->storeFile($request->project_logo, $project);
            }

            DB::commit();

            return $this->successResponse(message: "Website has been added successfully.");
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    private function storeFile($file, Project $project)
    {
        $rootPath = 'projects/' . $project->id . '/';

        $path = 'project_' . time() . (string) random_int(0, 5) . '.' . $file->getClientOriginalExtension();
        $file->storeAs("public/{$rootPath}", $path);

        $project->fill([
            'project_logo_path' => $rootPath . $path,
        ])->save();
    }
}