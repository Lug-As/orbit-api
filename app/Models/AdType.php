<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    use HasFactory;

    public function accounts()
    {
        return $this->belongsToMany(Account::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
