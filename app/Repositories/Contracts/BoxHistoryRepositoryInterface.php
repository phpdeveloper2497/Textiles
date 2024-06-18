<?php

namespace App\Repositories\Contracts;

use App\Models\BoxHistory;
use http\Env\Request;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoxHistoryRepositoryInterface.
 */
interface BoxHistoryRepositoryInterface
{
    public function all(Request $request, BoxHistory $boxHistory);

    public function update(Request $request, BoxHistory $boxHistory);
}
