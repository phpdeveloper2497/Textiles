<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoxHistoryResource;
use App\Http\Resources\StoreBoxHistoryResource;
use App\Jobs\Recalculate;
use App\Models\Box;
use App\Models\BoxHistory;
use App\Http\Requests\StoreBoxHistoryRequest;
use App\Http\Requests\UpdateBoxHistoryRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BoxHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index(Request $request)
    {
        $boxHistory = BoxHistory::with('box');

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

        if ($request->sortBy && in_array($request->sortBy, ['id', 'created_at'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'id';
        }
        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }
        $history = $boxHistory->orderBy($sortBy, $sortOrder)->paginate(20);
        return $this->reply(BoxHistoryResource::collection($history));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxHistoryRequest $request)
    {
        $boxHistory = BoxHistory::create([
            "box_id" => $request->box_id,
            "user_id" => $request->user()->id,
            "in_storage" => $request->in_storage,
            "out_storage" => $request->out_storage,
            "returned" => $request->returned,
            "per_pc_meter" => $request->per_pc_meter,
            "pc" => $request->pc,
            "length" => $request->per_pc_meter * $request->pc,
            "commentary" => $request->commentary
        ]);

        $current_time = Carbon::now();
        $target_time_end_day = Carbon::today()->setHour(22)->setMinute(59)->setSecond(0);
        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(00)->setSecond(0);

        if ($current_time >= $target_time_start_day && $current_time <= $target_time_end_day) {
            $box = Box::query()->where('id', '=', $request->box_id)->first();
            if ($request->in_storage === true) {
                $box->increment('remainder', $boxHistory->length);
            }
            if ($request->returned === true) {
                $box->increment('remainder', $boxHistory->length);
            }
            if ($request->out_storage === true) {
                if ($box->remainder > 0 && $request->per_pc_meter == $box->per_pc_meter)  {
                    $box->decrement('remainder', $boxHistory->length);
                } else {
                    return "Omborda ushbu materialdan siz so'rayotgan o'lcham yoki so'ralayotgan miqdorda mavjud emas.";
                }
            }
//            Recalculate::dispatch($boxHistory);
            return $this->success('Box history done successfully', new StoreBoxHistoryResource($boxHistory));
        } else {
            return "Hozir hisobot kiritish vaqtidan tashqari vaqt, hisobot davri 7:00 dan 22:59 gacha ";
        }

    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request, BoxHistory $boxHistory)
     {

     }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxHistoryRequest $request, BoxHistory $boxHistory)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BoxHistory $boxHistory)
    {
        if (auth()->user()) {
            $boxHistory->delete();
            return $this->success('Box History deleted successfully');
        }
    }

    public function workshop()
    {
        $boxHistories = BoxHistory::all();
        $boxHistoryReport = $boxHistories->where("out_storage",1)
            ->where("created_at", Carbon::now()->startOfDay())->first()->length;

        //TODO: $boxHistoryReport->length null bo'lganda degan xatolik tekshirish
        if (!$boxHistories)
        {
            return response()->json(['error' => 'Box not found'], 404);
        }
        if (!$boxHistoryReport->length)
        {
            return response()->json(['error' => 'No box history found for today'], 404);
        }
        //TODO: $boxHistoryReport ni resoursce orqali qaytarish
        return $boxHistoryReport;
    }
}
