<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoxHistoryResource;
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
            "user_id" => $request->user_id,
            "in_storage" => $request->in_storage,
            "out_storage" => $request->out_storage,
            "returned" => $request->returned,
            "per_pc_meter" => $request->per_pc_meter,
            "pc" => $request->pc,
            "length" => $request->length,
            "commentary" => $request->commentary
        ]);
        return $this->success('Boxhistory done successfully', $boxhistory);
    }

    /**
     * Display the specified resource.
     */
    public function show(BoxHistory $boxHistory)
    {
        return $this->reply(new BoxHistoryResource($boxHistory));
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
            "length" => $request->length,
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
