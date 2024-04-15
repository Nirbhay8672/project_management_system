<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFormRequest;
use App\Http\Requests\UserProfileFormRequest;
use App\Http\Services\UserService;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index(): Response
    {
        $permissions = Permission::select([
            'id',
            'name',
            'display_name',
            'category',
        ])->get();

        $grouped_permissions = $permissions->groupBy('category')->toArray();

        return Inertia::render('user/Index', [
            'permissions' => $grouped_permissions,
        ]);
    }

    public function profile(): Response
    {
        $user = User::find(Auth::user()->id);

        $user->load('profileImage');
        $user->append('profile_path');

        $data = $user->only([
            'id',
            'profile_path',
            'username',
            'email',
            'first_name',
            'last_name',
        ]);

        return Inertia::render('user/profile/Index', [
            'user_details' => $data,
        ]);
    }

    public function updateProfile(UserProfileFormRequest $request, User $user, UserService $userService): JsonResponse
    {
        try {
            DB::beginTransaction();

            $userService->profileUpdate($request->all());
            $user->refresh();

            $user->append('profile_path');

            $data = $user->only([
                'id',
                'profile_path',
                'username',
                'email',
                'first_name',
                'last_name',
            ]);

            DB::commit();

            return $this->successResponse(message: "Your Profile has been updated.", data: [
                'user_details' => $data,
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function datatable(Request $request): JsonResponse
    {
        try {
            $search = $request->search;
            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;

            $query = User::query();

            $query->with(['profileImage']);

            if ($search) {
                $query->where('username', 'like', '%' . $search . '%');
                $query->orWhere('email', 'like', '%' . $search . '%');
            }

            $total = $query->count();
            $offset = ($page - 1) * $perPage;

            $users = $query->offset($offset)
                ->limit($perPage)
                ->get();

            $total_pages = ceil($total / $perPage);

            $startIndex = ($page - 1) * $perPage;
            $endIndex = min($startIndex + $perPage, $total);

            return $this->successResponse(message: "User details.", data: [
                'users' => $users,
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

    public function createOrUpdate(UserFormRequest $request, User $user, UserService $userService): JsonResponse
    {
        try {
            DB::beginTransaction();

            $user->fill($request->fields())->save();

            if ($request->profile_image) {
                $userService->storeProfileImage($request->profile_image, $user);
            }

            DB::commit();

            return $this->successResponse(message: "{$user->username} has been {$request->action()} successfully.");
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->errorResponse(message: $exception->getMessage());
        }
    }

    public function delete(User $user): JsonResponse
    {
        try {
            DB::beginTransaction();

            $roleIdCountInUsers = Project::where('user_id', $user->id)->get()->count();

            if ($roleIdCountInUsers > 0) {
                return $this->errorResponse(message: "{$user->username} user is in use.", status: 422);
            }

            $user->delete();

            DB::commit();

            return $this->successResponse(message: "{$user->username} role has been deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}