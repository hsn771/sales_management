<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rode extends Model
{
    protected $fillable = ['name'];

    public function targets()
    {
        return $this->hasMany(Target::class);
    }
}
