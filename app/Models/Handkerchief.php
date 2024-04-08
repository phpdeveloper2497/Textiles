<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handkerchief extends Model
{
    use HasFactory;

    protected $fillable = ['name','sort_plane','all_products','finished_products','defective_products'];

    public function handkerchiefHistory()
    {
        return $this->hasMany(HandkerchiefHistory::class);
    }
}
