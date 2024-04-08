<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoldHandkerchiefRequest;
use App\Http\Resources\HandkerchiefHistoryResource;
use App\Models\Handkerchief;
use App\Models\HandkerchiefHistory;
use App\Http\Requests\StoreHandkerchiefHistoryRequest;
use App\Http\Requests\UpdateHandkerchiefHistoryRequest;
use Illuminate\Http\Request;

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
//        if ($request->filled('sold_out')) {
//            $handkerchiefHistoriy->where("sold_out", $request->get('sold_out'));
//        }
        //TODO: filter datetime
        $history = $handkerchiefHistoriy->orderBy('created_at')->paginate(15);

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
            'defective_products' => $request->defective_products,
        ]);
        if ($request->storage_in === true) {
            $handkerchief = Handkerchief::find($request->handkerchief_id);
            $handkerchief->all_products += $handkerchiefHistoriy->all_products;
            $handkerchief->finished_products += $handkerchiefHistoriy->finished_products;
            $handkerchief->defective_products += $handkerchiefHistoriy->defective_products;
            $handkerchief->save();
        }

        return new HandkerchiefHistoryResource($handkerchiefHistoriy);
    }

    /**
     * Display the specified resource.
     */
    public function show(HandkerchiefHistory $handkerchiefHistoriy)
    {
        // TODO:qilish kereak
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
            'storage_in' => $request->storage_in,
            'all_products' => $request->all_products,
            'finished_products' => $request->finished_products,
            'defective_products' => $request->defective_products,
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
