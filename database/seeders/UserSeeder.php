<?php

namespace Database\Seeders;

use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Permission::truncate();
        User::truncate();

        $admin = User::create([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123'),
        ]);

        $this->storeFile([$admin->id]);

        $permissions = [

            // user permissions
            ['display_name' => 'View User', 'name' => 'view_user', 'category' => 'User',],
            ['display_name' => 'Add User', 'name' => 'add_user', 'category' => 'User',],
            ['display_name' => 'Update User', 'name' => 'update_user', 'category' => 'User',],
            ['display_name' => 'User Info', 'name' => 'user_info', 'category' => 'User',],
            ['display_name' => 'Delete User', 'name' => 'delete_user', 'category' => 'User',],
            ['display_name' => 'Update Profile', 'name' => 'update_profile', 'category' => 'User',],

            // project permissions
            ['display_name' => 'View Project', 'name' => 'view_project', 'category' => 'Project',],
            ['display_name' => 'Add Website', 'name' => 'add_website', 'category' => 'Project',],
            ['display_name' => 'Sync Websites', 'name' => 'sync_websites', 'category' => 'Project',],
        ];

        if (Schema::hasTable('permissions')) {
            foreach ($permissions as $permission) {
                $permission_obj = Permission::create([
                    'name' => $permission['name'],
                    'display_name' => $permission['display_name'],
                    'category' => $permission['category'],
                ]);

                $admin->givePermissionTo($permission_obj);
            }
        } else {
            dd('permissions table is not exists.');
        }
    }

    public function storeFile($user_ids)
    {
        Storage::deleteDirectory('/public/user');

        $files = File::files(public_path('images'));
        $file = null;

        foreach ($files as $value) {
            if ($value->getFilename() === 'user.png') {
                $file = $value;
            }
        }

        if (!is_null($file)) {

            foreach ($user_ids as $user_id) {
                $path = 'user_' . time() . (string) random_int(0, 5) . '.' . $file->getExtension();

                Storage::disk('public')->put('user/' . $user_id . '/' . $path, $file->getContents());

                $profile_image = new ProfileImage;

                $profile_image->fill([
                    'user_id' => $user_id,
                    'file_name' => $file->getFilename(),
                    'file_path' => 'user/' . $user_id . '/' . $path,
                    'file_extension' => $file->getExtension(),
                    'file_size' => $file->getSize(),
                ])->save();
            }
        }
    }
}