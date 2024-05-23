<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $appends = [
        'short_name',
        'full_name',
        'picture_path',
    ];
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'salt',
        'phone',
        'role',
        'active',
        'picture',

    ];
    protected $hidden = ['password', 'salt'];

    

    public function getShortNameAttribute()
    {
        $exclude = [' de ', ' da ', ' do ', ' dos ', ' e '];
        $nameBrokenIntoParts = explode(" ", str_replace($exclude, " ", $this->fist_name.$this->last_name));
        if (count($nameBrokenIntoParts) > 1) {
            return $nameBrokenIntoParts[0] . " " . $nameBrokenIntoParts[count($nameBrokenIntoParts) - 1];
        }
        return $nameBrokenIntoParts[0];
    }

    public function getFullNameAttribute()
    {
        
        return $this->first_name.' '.$this->last_name;
    }

    public function getPicturePathAttribute()
    {
        return empty($this->picture) ? '/assets/images/no-photo.png' : '/assets/images/users/' . $this->picture;
    }
}