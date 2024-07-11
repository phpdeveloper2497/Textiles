<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoldHandkerchiefRequest;
use App\Http\Resources\SoldHandkerchiefResource;
use App\Models\Handkerchief;
use App\Models\HandkerchiefHistory;
use App\Models\SoldHankerchief;
use App\Policies\SoldHandkerchiefPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SoldHankerchiefController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SoldHandkerchiefRequest $request)
    {
        $handkerchief = Handkerchief::findOrFail($request->handkerchief_id);
        if (!Gate::allows('create', SoldHandkerchiefPolicy::class)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            if ($request->sold_out === true && $request->sold_products < $handkerchief->finished_products && $request->sold_defective_products < $handkerchief->defective_products) {
                $soldhendkerchief = SoldHankerchief::create([
                    'user_id' => $request->user()->id,
                    'handkerchief_id' => $request->handkerchief_id,
                    "sold_out" => $request->sold_out,
                    "company" => $request->company,
                    "address" => $request->address,
                    "premium_product" => $request->sold_products,
                    "first_product" => $request->sold_defective_products]);
            } else {
                return $this->error('Omborda ushbu mahsulotdan siz so\'rayotgan miqdorda mavjud emas');
            }

            if ($request->sold_out === true && $soldhendkerchief->sold_products < $handkerchief->finished_products && $soldhendkerchief->sold_defective_products < $handkerchief->defective_products) {
                $handkerchief->finished_products -= $soldhendkerchief->sold_products;
                $handkerchief->defective_products -= $soldhendkerchief->sold_defective_products;
                $handkerchief->save();
            } else {
                return 'Mahsulot yetarli emas';
            }
            return new SoldHandkerchiefResource($soldhendkerchief);
//        return $this->success('Sotilgan mahsulot', $handkerchiefHistory);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, SoldHankerchief $soldhankerchief)
    {

        dd('ol');
//        $handkerchiefHistory = HandkerchiefHistory::find($r
//        Gate::authorize('view', SoldHankerchief::class);
        $query = $handkerchiefHistory->newQuery();
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
    public function update(Request $request, SoldHankerchief $soldHankerchief)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SoldHankerchief $soldHankerchief)
    {
        //
    }
}
