<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Handkerchief extends Model
{
    use HasFactory;

    protected $fillable = ['name','box_id','sort_plane','all_products','finished_products','defective_products','not_packaged'];

    public function handkerchiefHistories() :HasMany
    {
        return $this->hasMany(HandkerchiefHistory::class);
    }

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class);
    }

    public function soldhendkerchief() :HasOne
    {
        return $this->hasOne(SoldHankerchief::class);
    }
}
