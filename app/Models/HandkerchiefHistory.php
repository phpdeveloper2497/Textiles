<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandkerchiefHistory extends Model
{
    use HasFactory;

    protected $fillable = ['handkerchief_id', 'user_id', 'handkerchief_name','storage_in', 'all_products', 'finished_products', 'defective_products','sold_out','sold_products','sold_defective_products'];
//protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handkerchief(): BelongsTo
    {
        return $this->belongsTo(Handkerchief::class);
    }
}
