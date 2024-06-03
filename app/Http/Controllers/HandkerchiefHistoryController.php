<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoldHandkerchiefRequest;
use App\Http\Resources\HandkerchiefHistoryResource;
use App\Models\Handkerchief;
use App\Models\HandkerchiefHistory;
use App\Http\Requests\StoreHandkerchiefHistoryRequest;
use App\Http\Requests\UpdateHandkerchiefHistoryRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HandkerchiefHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $handkerchiefHistoriy = HandkerchiefHistory::with('handkerchief');

        if ($request->filled('storage_in')) {
            $handkerchiefHistoriy->where("storage_in", $request->get('storage_in'));
        }
        if ($request->filled('sold_out')) {
            $handkerchiefHistoriy->where('sold_out', $request->get('sold_out'));
        }

        if ($request->user_id) {
            $handkerchiefHistoriy->where('user_id', $request->user_id);
        }

        if ($request->handkerchief_id) {
            $handkerchiefHistoriy->where('handkerchief_id', $request->handkerchief_id);
        }

        if ($request->from || $request->to) {
            $startDate = Carbon::parse($request->from)->startOfDay();
            $endDate = Carbon::parse($request->to)->endOfDay();
            $handkerchiefHistoriy->whereBetween('created_at', [$startDate, $endDate])->get();
        }

        if($request->sortBy && in_array($request->sortBy, ['id', 'created_at'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'id';
        }
        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }

        $history = $handkerchiefHistoriy->orderBy($sortBy, $sortOrder)->paginate(15);

        return HandkerchiefHistoryResource::collection($history);
    }


    public function store(StoreHandkerchiefHistoryRequest $request)
    {
        $handkerchiefHistoriy = HandkerchiefHistory::create([
            'user_id' => $request->user_id,
            'handkerchief_id' => $request->handkerchief_id,
            'storage_in' => $request->storage_in,
            'all_products' => $request->all_products,
            'finished_products' => $request->finished_products,
            'defective_products' => $request->defective_products
        ]);

        $current_time = Carbon::now();
        $target_time_end_day = Carbon::today()->setHour(22)->setMinute(59)->setSecond(0);
        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(0)->setSecond(0);

        if ($current_time >= $target_time_start_day && $current_time <= $target_time_end_day) {
            if ($request->storage_in === true) {
                $handkerchief = Handkerchief::find($request->handkerchief_id);
                $handkerchief->all_products += $handkerchiefHistoriy->all_products;
                $handkerchief->finished_products += $handkerchiefHistoriy->finished_products;
                $handkerchief->defective_products += $handkerchiefHistoriy->defective_products;
                $handkerchief->not_packaged += $handkerchief->all_products - $handkerchief->finished_products - $handkerchief->defective_products;
                $handkerchief->save();
            }
            return new HandkerchiefHistoryResource($handkerchiefHistoriy);
        } else {
            return "Hozir hisobot kiritish vaqtidan tashqari vaqt, hisobot davri 7:00 dan 22:59 gacha ";
        }
    }

    /**
     * Display the specified resource.
     */
//    public function show(Request $request, HandkerchiefHistory $handkerchiefHistoriy) :LengthAwarePaginator
//    {
//        if ($request->filled('storage_in')) {
//            $handkerchiefHistoriy->where("storage_in", $request->get('storage_in'));
//        }
//        if ($request->filled('sold_out')) {
//            $handkerchiefHistoriy->where('sold_out', $request->get('sold_out'));
//        }
//        if ($request->user_id) {
//            $handkerchiefHistoriy->where('user_id', $request->user_id);
//        }
//        if($request->sortBy && in_array($request->sortBy, ['id', 'created_at'])) {
//            $sortBy = $request->sortBy;
//        } else {
//            $sortBy = 'id';
//        }
//        if ($request->sortOrder && in_array($request->sortOrder,['asc', 'desc'])) {
//            $sortOrder = $request->sortOrder;
//        } else {
//            $sortOrder = 'desc';
//        }
//        $history = $handkerchiefHistoriy->orderBy($sortBy,$sortOrder)->paginate(15);
//        return new HandkerchiefHistoryResource($history);
//    }

    public function show(Request $request, HandkerchiefHistory $handkerchiefHistoriy) : LengthAwarePaginator
    {
        $query = $handkerchiefHistoriy->newQuery();

        if ($request->filled('storage_in')) {
            $query->where("storage_in", $request->get('storage_in'));
        }
        if ($request->filled('sold_out')) {
            $query->where('sold_out', $request->get('sold_out'));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        $sortBy = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'desc');

        if (!in_array($sortBy, ['id', 'created_at'])) {
            $sortBy = 'id';
        }
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $history = $query->orderBy($sortBy, $sortOrder)->paginate(15);

        return $history;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHandkerchiefHistoryRequest $request, HandkerchiefHistory $handkerchiefHistoriy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HandkerchiefHistory $handkerchiefHistoriy)
    {
        $handkerchiefHistoriy->delete();
        return "$handkerchiefHistoriy->id HandkerchiefHistoriy deleted";
    }

    public function sold(SoldHandkerchiefRequest $request, Handkerchief $handkerchief)
    {
        $handkerchiefHistoriy = HandkerchiefHistory::create([
            'user_id' => $request->user_id,
            'handkerchief_id' => $request->handkerchief_id,
            'storage_in' => 0,
            'all_products' => 0,
            'finished_products' => 0,
            'defective_products' => 0,
            "sold_out" => $request->sold_out,
            "sold_products" => $request->sold_products,
            "sold_defective_products" => $request->sold_defective_products]);

        if ($request->sold_out === true && $handkerchiefHistoriy->sold_products < $handkerchief->finished_products && $handkerchiefHistoriy->sold_defective_products < $handkerchief->defective_products) {
            $handkerchief->finished_products -= $handkerchiefHistoriy->sold_products;
            $handkerchief->defective_products -= $handkerchiefHistoriy->sold_defective_products;
            $handkerchief->save();
        } else {
            return 'the product is not enough';
        }
        return $this->success('Product sold', $handkerchiefHistoriy);
    }
}
