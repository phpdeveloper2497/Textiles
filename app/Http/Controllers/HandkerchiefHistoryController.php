<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowHandkerchiefHistoryRequest;
use App\Http\Requests\SoldHandkerchiefRequest;
use App\Http\Resources\HandkerchiefHistoryResource;
use App\Http\Resources\ShowHandkerchiefResource;
use App\Http\Resources\SoldHandkerchiefResource;
use App\Models\Box;
use App\Models\BoxHistory;
use App\Models\Handkerchief;
use App\Models\HandkerchiefHistory;
use App\Http\Requests\StoreHandkerchiefHistoryRequest;
use App\Http\Requests\UpdateHandkerchiefHistoryRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;

class HandkerchiefHistoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        Gate::authorize('viewAny', HandkerchiefHistory::class);
        $handkerchiefHistory = HandkerchiefHistory::with('handkerchief');

        if ($request->filled('storage_in')) {
            $handkerchiefHistory->where("storage_in", $request->get('storage_in'));
        }
        if ($request->filled('sold_out')) {
            $handkerchiefHistory->where('sold_out', $request->get('sold_out'));
        }

        if ($request->user_id) {
            $handkerchiefHistory->where('user_id', $request->user_id);
        }

        if ($request->handkerchief_id) {
            $handkerchiefHistory->where('handkerchief_id', $request->handkerchief_id);
        }

        if ($request->from || $request->to) {
            $startDate = Carbon::parse($request->from)->startOfDay();
            $endDate = Carbon::parse($request->to)->endOfDay();
            $handkerchiefHistory->whereBetween('created_at', [$startDate, $endDate])->get();
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

        $history = $handkerchiefHistory->with('user')->orderBy($sortBy, $sortOrder)->paginate(15);

        return HandkerchiefHistoryResource::collection($history);
    }


    public function store(StoreHandkerchiefHistoryRequest $request)
    {
        Gate::authorize('create', HandkerchiefHistory::class);
        $handkerchief = Handkerchief::find($request->handkerchief_id);

        if ($handkerchief->box->boxHistories->where("out_storage", "=", true)) {
            $handkerchiefHistory = HandkerchiefHistory::create([
                'user_id' => $request->user()->id,
                'handkerchief_id' => $request->handkerchief_id,
                'storage_in' => $request->storage_in,
//                'all_products' => $request->all_products,
                'finished_products' => $request->finished_products,
                'defective_products' => $request->defective_products
            ]);

        }
        $current_time = Carbon::now();
        $target_time_end_day = Carbon::today()->setHour(22)->setMinute(59)->setSecond(0);
        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(0)->setSecond(0);

        if ($current_time >= $target_time_start_day && $current_time <= $target_time_end_day) {
            if ($request->storage_in === true) {
                $handkerchief = Handkerchief::find($request->handkerchief_id);
                $handkerchief->all_products += $handkerchiefHistory->all_products;
                $handkerchief->finished_products += $handkerchiefHistory->finished_products;
                $handkerchief->defective_products += $handkerchiefHistory->defective_products;
                $handkerchief->save();
            }
            return new HandkerchiefHistoryResource($handkerchiefHistory);
        } else {
            throw ValidationException::withMessages([
                'message' => "Hozir hisobot kiritish vaqtidan tashqari vaqt, hisobot davri 7:00 dan 22:59 gacha "
            ]);
        }
    }


    public function show(Request $request, HandkerchiefHistory $handkerchiefHistory): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        Gate::authorize('view', $handkerchiefHistory);
        $query = $handkerchiefHistory->newQuery();
        if ($request->filled('handkerchief_id')) {
            $query->where("handkerchief_id", $request->get('handkerchief_id'));
        }
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
//        return ShowHandkerchiefResource::collection($history);
        return $history;

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHandkerchiefHistoryRequest $request, HandkerchiefHistory $handkerchiefHistory)
    {
        Gate::authorize('update', HandkerchiefHistory::class);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HandkerchiefHistory $handkerchiefHistory)
    {
        if (!Gate::authorize('delete', $handkerchiefHistory)) {
            throw ValidationException::withMessages([
                'message' => "Sizda bu yerga kirish uchun ruxsat yo'q"
            ]);
        } else {
            $handkerchiefHistory->delete();
            return "Id raqami $handkerchiefHistory->id ga teng bo'lgan tarix o'chirib yuborildi";
        }
    }

    public function sold(SoldHandkerchiefRequest $request, HandkerchiefHistory $handkerchiefHistory)
    {
        $handkerchief = Handkerchief::findOrFail($request->handkerchief_id);
        if (!Gate::allows('sold', HandkerchiefHistory::class)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            if ($request->sold_out === true && $request->sold_products < $handkerchief->finished_products && $request->sold_defective_products < $handkerchief->defective_products) {
                $handkerchiefHistory = HandkerchiefHistory::create([
                    'user_id' => $request->user()->id,
                    'handkerchief_id' => $request->handkerchief_id,
                    'storage_in' => 0,
//                    'all_products' => 0,
                    'finished_products' => 0,
                    'defective_products' => 0,
                    "sold_out" => $request->sold_out,
                    "sold_products" => $request->sold_products,
                    "sold_defective_products" => $request->sold_defective_products
                ]);
            } else {
                throw ValidationException::withMessages([
                    'message' => "Omborda ushbu mahsulotdan siz so'rayotgan miqdorda mavjud emas "
                ]);
            }

            if ($request->sold_out === true && $handkerchiefHistory->sold_products < $handkerchief->finished_products && $handkerchiefHistory->sold_defective_products < $handkerchief->defective_products) {
                $handkerchief->finished_products -= $handkerchiefHistory->sold_products;
                $handkerchief->defective_products -= $handkerchiefHistory->sold_defective_products;
                $handkerchief->save();
            } else {
                throw ValidationException::withMessages([
                    'message' => "Mahsulot yetarli emas "
                ]);
            }
            return new SoldHandkerchiefResource($handkerchiefHistory);
        }
    }
}
