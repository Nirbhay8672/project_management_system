<?php

namespace Database\Seeders;

use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        $admin = User::create([
            'username' => 'admin',
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin@123'),
        ]);

        $this->storeFile([$admin->id]);
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