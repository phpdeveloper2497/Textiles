<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoxHistoryResource;
use App\Http\Resources\StoreBoxHistoryResource;
use App\Models\Box;
use App\Models\BoxHistory;
use App\Http\Requests\StoreBoxHistoryRequest;
use App\Http\Requests\UpdateBoxHistoryRequest;

class BoxHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        $boxhistory = BoxHistory::all();
        return $this->reply(BoxHistoryResource::collection($boxhistory));
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
        if ($request->in_storage === true || $request->returned === true) {
            Box::query()->where('id', '=', $request->box_id)->first()->increment('remainder', $boxhistory->length);
        }

        if ($request->out_storage === true) {
            $box = Box::query()->where('id', '=', $request->box_id)->first();
            if ($box->remainder > 0 && $boxhistory->length <= $box->remainder) {
                $box->remainder -= $boxhistory->length;
                $box->save();
            } else {
                return 'the product is not enough';
            }
        }
        return $this->success('Boxhistory done successfully', new StoreBoxHistoryResource($boxhistory));
    }

    /**
     * Display the specified resource.
     */
    public function show(BoxHistory $boxHistory)
    {

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
