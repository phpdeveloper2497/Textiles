<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialRequest;
use App\Http\Resources\BoxResource;
use App\Models\Box;
use App\Http\Requests\StoreBoxRequest;
use App\Http\Requests\UpdateBoxRequest;

class BoxController extends Controller
{

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
            'remainder' => $request->get('remainder'),
            'sort_by' => $request->get('sort_by')
        ]);
        return $this->success('Box created successfully', $box);
    }

    /**
     * Display the specified resource.
     */
    public function show(Box $box)
    {
        $box = Box::find($box);
        return $this->reply(new BoxResource($box));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxRequest $request, Box $box)
    {
        if ($box) {
            $box->name = $request->get('name');
            $box->per_liner_meter = $request->get('per_liner_meter');
            $box->remainder = $request->get('remainder');
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

    public function addMaterial(MaterialRequest $request,$id)
    {
        $box = Box::find($id);
       if ($box)
       {
           $add_material = $request->length_material * $request->quantity;
           Box::query()->where('id', '=', $request->id)->increment('remainder', $add_material);
           return $this->success("Box $box->id to added material");
       }

    }
}
