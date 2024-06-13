<?php

namespace App\Repositories\Contracts;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoxRepositoryInterface.
 */
interface BoxRepositoryInterface
{
    public function create();
   public function show();
   public function delete();
}
