<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Http\Requests\ShowBoxRequest;
use App\Http\Resources\BoxResource;
use App\Http\Resources\ShowBoxHistoryResource;
use App\Http\Resources\StoreBoxHistoryResource;
use App\Models\Box;
use App\Http\Requests\StoreBoxRequest;
use App\Http\Requests\UpdateBoxRequest;
use App\Models\BoxHistory;

class BoxController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        $box = Box::all();
        return $this->reply(BoxResource::collection($box));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxRequest $request)
    {
        $box = Box::create([
            'name' => $request->get('name'),
            'per_liner_meter' => $request->get('per_liner_meter'),
            'sort_by' => $request->get('sort_by')
        ]);
        return $this->success('Box created successfully', $box);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowBoxRequest $request, Box $box)
    {
//        if ($box->boxHistory->count() > 0) {

            if ($request->in_storage == 1) {
                return StoreBoxHistoryResource::collection($box->boxhistory()->where('in_storage', '=', true)->get());
            }
            if ($request->out_storage == 1) {
                return StoreBoxHistoryResource::collection($box->boxhistory()->where('out_storage', '=', true)->get());
            }
            if ($request->returned == 1) {
                return StoreBoxHistoryResource::collection($box->boxhistory()->where('returned', '=', true)->get());
            }
            return new BoxResource($box);
//        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxRequest $request, Box $box)
    {
        if ($box) {
            $box->name = $request->get('name');
            $box->per_liner_meter = $request->get('per_liner_meter');
            $box->sort_by = $request->get('sort_by');
            $box->save();
            return $this->success("$box->id box updated", $box);
        }
    }

    public function destroy(Box $box)
    {
        $box->delete();
        return $this->success("Box $box->id deleted");
    }
}
