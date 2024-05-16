<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowHandkerchiefRequest;
use App\Http\Resources\HandkerchiefHistoryResource;
use App\Http\Resources\HandkerchiefResource;
use App\Http\Resources\StoreHandkerchiefResource;
use App\Models\Handkerchief;
use App\Http\Requests\StoreHandkerchiefRequest;
use App\Http\Requests\UpdateHandkerchiefRequest;
use Illuminate\Http\Request;

class HandkerchiefController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        $handkerchief = Handkerchief::all();
        return HandkerchiefResource::collection($handkerchief);
    }


    public function store(StoreHandkerchiefRequest $request)
    {
        $handkerchief = Handkerchief::create([
            "box_id" => $request->box_id,
            "name" => $request->name,
            "sort_plane" => $request->sort_plane,
        ]);
        return $this->reply($handkerchief);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Handkerchief $handkerchief)
    {
//        if ($request->filled('finished_products')) {
//            $handkerchief->where('finished_products', $request->get('finished_products'));
//        }
        $history = $handkerchief->orderBy('finished_products');
        return $this->reply($history);
    }


    public function update(UpdateHandkerchiefRequest $request, Handkerchief $handkerchief)
    {
            $handkerchief->name = $request->name;
            $handkerchief->sort_plane = $request->sort_plane;
            $handkerchief->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Handkerchief $handkerchief)
    {
        $handkerchief->delete();
        return "$handkerchief->id handkerchief deleted successfully";
    }
}
