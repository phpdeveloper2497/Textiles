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
//        dd($request);
        $boxhistory = BoxHistory::with('box');

        if ($request->filled('in_storage')) {
            $boxhistory->where("in_storage", $request->get('in_storage'));
        }
        if ($request->filled('out_storage')) {
            $boxhistory->where("out_storage", $request->get('out_storage'));
        }
        if ($request->filled('returned')) {
            $boxhistory->where("returned", $request->get('returned'));
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

        $history = $boxhistory->orderBy($sortBy, $sortOrder)->paginate(15);

        return $this->reply(BoxHistoryResource::collection($history));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxHistoryRequest $request)
    {
        $boxhistory = BoxHistory::create([
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
        $target_time_end_day = Carbon::today()->setHour(23)->setMinute(30)->setSecond(0);
        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(30)->setSecond(0);

        if ($current_time >= $target_time_start_day && $current_time <= $target_time_end_day) {
            if ($request->in_storage === true) {
                Box::query()->where('id', '=', $request->box_id)->first()->increment('remainder', $boxhistory->length);
            }
            if ($request->returned === true) {
                Box::query()->where('id', '=', $request->box_id)->first()->increment('remainder', $boxhistory->length);
            }
            if ($request->out_storage === true && $request->per_pc_meter) {
                $box = Box::query()->where('id', '=', $request->box_id)->first();
                if ($box->remainder > 0 && $boxhistory->length <= $box->remainder) {
                    $box->remainder -= $boxhistory->length;
                    $box->save();
                } else {
                    return "Omborda siz istagan materialdan yetarlicha emas. Ushbu materialdan mavjud uzunlik $box->remainder metr";
                }
            }
//            Recalculate::dispatch($boxhistory);
            return $this->success('Box history done successfully', new StoreBoxHistoryResource($boxhistory));
        } else {
            return "Hozir hisobot kiritish vaqtidan tashqari vaqt, hisobot davri 7:30 dan 23:30 gacha ";
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, BoxHistory $boxHistory)
    {
        if ($boxHistory->box_id === $request->box->id) {
            if ($request->filled('in_storage')) {
                $boxHistory->where("in_storage", $request->get('in_storage'));
            }
            if ($request->filled('out_storage')) {
                $boxHistory->where("out_storage", $request->get('out_storage'));
            }
            if ($request->filled('returned')) {
                $boxHistory->where("returned", $request->get('returned'));
            }
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
        $history = $boxHistory->orderBy($sortBy, $sortOrder)->paginate(15);

        return $this->reply(BoxHistoryResource::collection($history));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxHistoryRequest $request, BoxHistory $boxHistory)
    {
        $boxHistory->update([
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
}
