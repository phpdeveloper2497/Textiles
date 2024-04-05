<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory;

    protected $fillable = ['name','per_liner_meter','remainder','sort_by'];

    public function boxHistory():HasMany
    {
        return $this->HasMany(BoxHistory::class);
    }
}
