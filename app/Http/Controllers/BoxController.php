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
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        if ($request->file('image')) {
            $path = $request->file('image')->store('boxes/' . $box->id, 'public');

           $box->image_path=$path;
           $box->save();
        };


        return $this->success('Box created successfully', $box);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowBoxRequest $request , Box $box)
    {

        if ($request->in_storage == 1) {
            return StoreBoxHistoryResource::collection($box->boxHistories()->where('in_storage', '=', true)->get());
        }
        if ($request->out_storage == 1) {
            return StoreBoxHistoryResource::collection($box->boxHistories()->where('out_storage', '=', true)->get());
        }
        if ($request->returned == 1) {
            return StoreBoxHistoryResource::collection($box->boxHistories()->where('returned', '=', true)->get());
        }

        // Box da per_pc_metr uzunlikdagi matodan qancha qolganligini ko'rsatish
        $results = BoxHistory::select('box_id', 'per_pc_meter','length',
            \DB::raw('SUM(CASE WHEN in_storage = true THEN pc ELSE 0 END) as total_pc_in_storage'),
            \DB::raw('SUM(CASE WHEN out_storage = true THEN pc ELSE 0 END) as total_pc_out_storage')
        )
            ->where('box_id', $request->box->id)
            ->groupBy('box_id', 'per_pc_meter','length')
            ->get();

        $finalResults = $results->map(function($result) {
            $remaining_pc = $result->total_pc_in_storage - $result->total_pc_out_storage;
            $length = $remaining_pc * $result->per_pc_meter ;

            return [
                'box_id' => $result->box_id,
                'per_pc_meter' => $result->per_pc_meter,
                'remaining_pc' => $remaining_pc,
                'length' => $length
            ];
        });

        return response()->json($finalResults);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBoxRequest $request, Box $box)
    {
    //        $box->name = $request->get('name');
//        $box->per_liner_meter = $request->get('per_liner_meter');
//        $box->sort_by = $request->get('sort_by');
//
//        if ($request->hasFile('image')) {
//            if ($box->image_path) {
//                Storage::delete($box->image_path);
//            }
//            $path = $request->file('image')->store('boxes/' . $box->id, 'public');
//            $box->image_path = $path;
//        }
//        $box->update();
//
//        return $this->success("$box->id box updated", $box);
    }

    public function destroy(Box $box)
    {
//        unlink('storage/' . $box->image_path);
        Storage::disk('public')->delete("$box->image_path");

        File::DeleteDirectory('storage/' . 'boxes/' . "$box->id");

        $box->delete();
        return $this->success("Box $box->id deleted");
    }

    public function workshop($id)
    {
        $box = Box::find($id);
        return $box->boxHistories->where("created_at", Carbon::now()->startOfDay())->first()->length;
    }
}
