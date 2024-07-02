<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckRequest;
use App\Http\Resources\BoxHistoryResource;
use App\Http\Resources\InprogressBoxHistoryResource;
use App\Http\Resources\StoreBoxHistoryResource;
use App\Jobs\Recalculate;
use App\Models\Box;
use App\Models\BoxHistory;
use App\Http\Requests\StoreBoxHistoryRequest;
use App\Http\Requests\UpdateBoxHistoryRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use function Laravel\Prompts\error;

class BoxHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', BoxHistory::class);
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
        Gate::authorize('create', BoxHistory::class);

        $current_time = Carbon::now();
        $target_time_end_day = Carbon::today()->setHour(22)->setMinute(59)->setSecond(0);
        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(00)->setSecond(0);

        $length = $request->per_pc_meter * $request->pc;

        if ($current_time >= $target_time_start_day && $current_time <= $target_time_end_day) {
            $box = Box::query()->where('id', '=', $request->box_id)->first();
            if ($request->in_storage === true) {
                $box->increment('remainder', $length);
            }
            if ($request->returned === true) {
                $box->increment('remainder', $length);
            }
            //////*******************************////////////////////////////////////////////////////////

            if ($request->out_storage === true) {
                if ($box->remainder > 0) {
                    $results = BoxHistory::select('box_id', 'per_pc_meter', 'pc')
                        ->where('box_id', $box->id)
                        ->groupBy('box_id', 'per_pc_meter', 'pc')
                        ->get();

                    $foundMatch = false;

                    foreach ($results as $result) {
                        if ($request->per_pc_meter === $result->per_pc_meter) {
//                            dd($result->pc > 0);
//                            dd( $request->pc <= $result->pc);
//                            dd( $request->pc);
//                            dd( $result);
//                            dd($result->pc > 0 && $request->pc <= $result->pc);
                            if ($result->pc > 0 && $request->pc <= $result->pc) {
                                $foundMatch = true;
                                $box->decrement('remainder', $length);
                                break;
                            } else {
                                return "Omborda ushbu materialdan so'ralayotgan miqdor (rulon)da mavjud emas.";
                            }
                        }
                    }

                    if (!$foundMatch) {
                        return "Omborda so'ralayotgan o'lchamdagi materialdan mavjud emas.";
                    }
                } else {
                    return "Omborda ushbu materialdan mavjud emas.";
                }
            }

            //////*******************************////////////////////////////////////////////////////////

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
            //            Recalculate::dispatch($boxHistory);
            return $this->success('Box history done successfully', new StoreBoxHistoryResource($boxHistory));

        } else {
            return "Hozir hisobot kiritish vaqtidan tashqari vaqt, hisobot davri 7:00 dan 22:59 gacha ";
        }

    }

    /**
     * Display the specified resource.
     */

    public
    function show(Request $request, BoxHistory $boxHistory)
    {
        Gate::authorize('view', BoxHistory::class);

    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(UpdateBoxHistoryRequest $request, BoxHistory $boxHistory)
    {
        Gate::authorize('update', BoxHistory::class);
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(BoxHistory $boxHistory)
    {
        Gate::authorize('delete', $boxHistory);
        if (auth()->user()) {
            $boxHistory->delete();
            return $this->success('Box History deleted successfully');
        }
    }

    public
    function workshop(BoxHistory $boxHistory)
    {
        if (!Gate::authorize('workshop', $boxHistory)) {
            return $this->reply('Sizda bu yerga kirish uchun ruxsat yo\'q');
        } else {
            $boxHistories = BoxHistory::all();
//            $start_day = Carbon::now()->startOfDay();
            $start_day = Carbon::today()->setHour(17)->setMinute(01)->setSecond(0);
            $end_day = Carbon::today()->setHour(17)->setMinute(05)->setSecond(0);
            $boxHistoryReport = $boxHistories->where("out_storage", "=", true)
                ->whereBetween('created_at', [$start_day, $end_day]);
            if ($boxHistoryReport->isEmpty()) {
                return $this->reply('Sexda ish jarayonida material yo\'q');
            } else {
                return new InprogressBoxHistoryResource($boxHistoryReport->first());
            }
        }
    }


    public
    function check(Request $request)
    {
//        dd($request);
        $results = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
        if ($request->has('a')) {
            $inputValue = $request->input('a');
            foreach ($results as $result) {
                if ($inputValue == $result) {
                    return $result;
                }
            }
        }
        return 'day';
    }
}
