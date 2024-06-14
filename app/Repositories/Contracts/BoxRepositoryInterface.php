<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\StoreBoxRequest;
use App\Models\Box;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

/**
 * Class BoxRepositoryInterface.
 */
interface BoxRepositoryInterface
{
    public function create(StoreBoxRequest $request);
   public function show();
   public function delete(Box $box);

   public function update(StoreBoxRequest $request, Box $box);
}
