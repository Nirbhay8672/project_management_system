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

        $user_ids = [];
        $user_objects = [];

        $admin = User::create([
            'username' => 'Admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123'),
        ]);

        array_push($user_ids, $admin->id);
        array_push($user_objects, $admin);

        $user_1 = User::create([
            'username' => 'Amit',
            'first_name' => 'Amit',
            'last_name' => 'Solanki',
            'email' => 'admit@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        array_push($user_ids, $user_1->id);
        array_push($user_objects, $user_1);

        $user_2 = User::create([
            'username' => 'Bharat',
            'first_name' => 'Bharat',
            'last_name' => 'Makwana',
            'email' => 'bharat@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        array_push($user_ids, $user_2->id);
        array_push($user_objects, $user_2);

        $user_3 = User::create([
            'username' => 'Rushil',
            'first_name' => 'Rushil',
            'last_name' => 'Pipaliya',
            'email' => 'rishi@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        array_push($user_ids, $user_3->id);
        array_push($user_objects, $user_3);

        $user_4 = User::create([
            'username' => 'Dilip',
            'first_name' => 'Dilip',
            'last_name' => 'Vadher',
            'email' => 'dilip@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        array_push($user_ids, $user_4->id);
        array_push($user_objects, $user_4);

        $user_5 = User::create([
            'username' => 'Raj',
            'first_name' => 'Raj',
            'last_name' => 'Soni',
            'email' => 'raj@gmail.com',
            'password' => bcrypt('123456'),
        ]);

        array_push($user_ids, $user_5->id);
        array_push($user_objects, $user_5);

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

                foreach ($user_objects as $user_object) {
                    $user_object->givePermissionTo($permission_obj);
                }
            }
        } else {
            dd('permissions table is not exists.');
        }

        $this->storeFile($user_ids);
    }

    public function storeFile($user_ids)
    {
        Storage::deleteDirectory('/public/user');

        $files = File::files(public_path('images'));
        $file = null;

        foreach ($files as $value) {
            if ($value->getFilename() === 'profile.png') {
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