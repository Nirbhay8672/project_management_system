<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getProfilePathAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}