<?php

namespace App\Repositories\Eloquent;

use App\Models\BoxHistory;
use App\Repositories\Contracts\BoxHistoryRepositoryInterface;
use Carbon\Carbon;
use http\Env\Request;

/**
 * Class BoxHistoryRepository.
 */
class BoxHistoryRepository implements BoxHistoryRepositoryInterface
{
        public function all(Request $request, BoxHistory $boxHistory)
        {
//            dd($request);
//            dd($boxHistory);
            if ($request->filled('in_storage')) {
                $boxHistory->where("in_storage", $request->get('in_storage'));
            }
            if ($request->filled('out_storage')) {
                $boxHistory->where("out_storage", $request->get('out_storage'));
            }
            if ($request->filled('returned')) {
                $boxHistory->where("returned", $request->get('returned'));
            }
            if ($request->filled('box_id')) {
                $boxHistory->where("box_id", $request->get('box_id'));
            }
            if ($request->from || $request->to) {
                $startDate = Carbon::parse($request->from)->startOfDay();
                $endDate = Carbon::parse($request->to)->endOfDay();
                $boxHistory->whereBetween('created_at', [$startDate, $endDate])->get();
            }

        }

        public function update(Request $request, BoxHistory $boxHistory)
        {
            $boxhistory->update([
                "box_id" => $request->box_id,
                "user_id" => $request->user_id,
                "in_storage" => $request->in_storage,
                "out_storage" => $request->out_storage,
                "returned" => $request->returned,
                "per_pc_meter" => $request->per_pc_meter,
                "pc" => $request->pc,
                "commentary" => $request->commentary
            ]);

        }
}
