<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function storeFile(UploadedFile $file, string $rootPath): string
    {
        $path = 'user_' . time() . (string) random_int(0, 5) . '.' . $file->getClientOriginalExtension();
        $file->storeAs("public/{$rootPath}", $path);

        return $rootPath . $path;
    }

    public function profileUpdate(array $usersDetails): void
    {
        $user = User::find((int) $usersDetails['user_id']);

        $user->fill([
            'username' => $usersDetails['username'],
            'first_name' => $usersDetails['first_name'],
            'last_name' => $usersDetails['last_name'],
            'email' => $usersDetails['email'],
        ]);

        if (!empty($usersDetails['password'])) {
            $user->password = bcrypt($usersDetails['password']);
        }

        $user->save();

        if ($usersDetails['profile_image'] ?? false) {
            $this->storeProfileImage($usersDetails['profile_image'], $user);
        }
    }

    public function storeProfileImage(UploadedFile $file, User $user): void
    {
        $user->loadMissing('profileImage');

        $profile_image = new ProfileImage();

        if ($user->profileImage != null) {
            Storage::delete('public/' . $user->profileImage['file_path']);
            $profile_image = ProfileImage::find((int) $user->profileImage['id']);
        }

        $profile_image->fill([
            'user_id' => $user->id,
            'file_name' => basename($file->getClientOriginalName(), '.' . $file->getClientOriginalExtension()),
            'file_path' => $this->storeFile($file, "user/{$user->id}/"),
            'file_extension' => $file->extension(),
            'file_size' => $file->getSize(),
        ])->save();
    }
}