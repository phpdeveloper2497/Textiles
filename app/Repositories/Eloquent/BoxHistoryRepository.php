<?php

namespace App\Repositories\Eloquent;

use App\Models\BoxHistory;
use App\Repositories\Contracts\BoxHistoryRepositoryInterface;
use Carbon\Carbon;
use http\Env\Request;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoxHistoryRepository.
 */
class BoxHistoryRepository implements BoxHistoryRepositoryInterface
{
        public function all(Request $request, BoxHistory $boxhistory)
        {
            if ($request->filled('in_storage')) {
                $boxhistory->where("in_storage", $request->get('in_storage'));
            }
            if ($request->filled('out_storage')) {
                $boxhistory->where("out_storage", $request->get('out_storage'));
            }
            if ($request->filled('returned')) {
                $boxhistory->where("returned", $request->get('returned'));
            }
            if ($request->filled('box_id')) {
                $boxhistory->where("box_id", $request->get('box_id'));
            }
            if ($request->from || $request->to) {
                $startDate = Carbon::parse($request->from)->startOfDay();
                $endDate = Carbon::parse($request->to)->endOfDay();
                $boxhistory->whereBetween('created_at', [$startDate, $endDate])->get();
            }

        }
}
