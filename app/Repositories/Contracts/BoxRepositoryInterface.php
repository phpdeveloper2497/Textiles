<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\StoreBoxRequest;
use App\Models\Box;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoxRepositoryInterface.
 */
interface BoxRepositoryInterface
{
    public function create(StoreBoxRequest $request);
   public function show();
   public function delete();
}
