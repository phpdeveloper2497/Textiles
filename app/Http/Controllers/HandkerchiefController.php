<?php

namespace App\Http\Controllers;

use App\Http\Requests\SoldHandkerchiefRequest;
use App\Http\Resources\HandkerchiefByIDResource;
use App\Http\Resources\HandkerchiefHistoryResource;
use App\Http\Resources\HandkerchiefResource;
use App\Http\Resources\StoreHandkerchiefResource;
use App\Models\Handkerchief;
use App\Http\Requests\StoreHandkerchiefRequest;
use App\Http\Requests\UpdateHandkerchiefRequest;
use App\Models\HandkerchiefHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class HandkerchiefController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth:sanctum");
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Handkerchief::class);
        $handkerchief = Handkerchief::all();
        return HandkerchiefResource::collection($handkerchief);
    }


    public function store(StoreHandkerchiefRequest $request)
    {
        Gate::authorize('create', Handkerchief::class);
        $handkerchief = Handkerchief::create([
            "box_id" => $request->box_id,
            "name" => $request->name,
            "sort_plane" => $request->sort_plane,
        ]);
        if ($request->file('image')) {
            $path = $request->file('image')->store('handkerchiefs/' . $handkerchief->id, 'public');
            $handkerchief->image_path = $path;
            $handkerchief->save();
        };
        return $this->reply($handkerchief);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Handkerchief $handkerchief)
    {
        if (!Gate::authorize('view', $handkerchief)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
//            if ($request->filled('storage_in')) {
//               return HandkerchiefHistoryResource::collection($handkerchief->handkerchiefHistories()->where("storage_in",'=', true)->get());
//            }
//            if ($request->filled('sold_out')) {
//                return HandkerchiefHistoryResource::collection($handkerchief->handkerchiefHistories()->where('sold_out', '=', true)->get());
//            }

            return new HandkerchiefByIDResource($handkerchief);
//            $history = $handkerchief->orderBy('finished_products');
////            dd($history);
//            return $this->reply($history);

        }
    }

    public function viewHandkerchiefHistory(Request $request, Handkerchief $handkerchief)
    {
        if (!Gate::authorize('view', $handkerchief)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            if ($request->filled('storage_in')) {
                return HandkerchiefHistoryResource::collection($handkerchief->handkerchiefHistories()->where("storage_in", '=', true)->get());
            }
            if ($request->filled('sold_out')) {
                return HandkerchiefHistoryResource::collection($handkerchief->handkerchiefHistories()->where('sold_out', '=', true)->get());
            }
        }
    }


    public function update(UpdateHandkerchiefRequest $request, Handkerchief $handkerchief)
    {
        Gate::authorize('update', Handkerchief::class);
        $handkerchief->name = $request->name;
        $handkerchief->sort_plane = $request->sort_plane;
        $handkerchief->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Handkerchief $handkerchief)
    {
        Gate::authorize('delete', Handkerchief::class);
        Storage::disk('public')->delete("$handkerchief->image_path");
        File::DeleteDirectory('storage/' . 'handkerchief/' . "$handkerchief->id");
        $handkerchief->delete();
        return "handkerchief deleted successfully";
    }

    public function sold(SoldHandkerchiefRequest $request, Handkerchief $handkerchief)
    {
//        dd($request->user()->id);
        $handkerchief = Handkerchief::findOrFail($request->id);
        if (!Gate::allows('sold', HandkerchiefHistory::class)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            $handkerchiefHistory = HandkerchiefHistory::create([
                'user_id' => $request->user()->name,
                'handkerchief_id' => $request->handkerchief_id,
                'storage_in' => 0,
                'all_products' => 0,
                'finished_products' => 0,
                'defective_products' => 0,
                "sold_out" => $request->sold_out,
                "sold_products" => $request->sold_products,
                "sold_defective_products" => $request->sold_defective_products]);

            if ($request->sold_out === true && $handkerchiefHistory->sold_products < $handkerchief->finished_products && $handkerchiefHistory->sold_defective_products < $handkerchief->defective_products) {
                $handkerchief->finished_products -= $handkerchiefHistory->sold_products;
                $handkerchief->defective_products -= $handkerchiefHistory->sold_defective_products;
                $handkerchief->save();
            } else {
                return 'Mahsulot yetarli emas';
            }
            return $this->success('Sotilgan mahsulot', $handkerchiefHistory);
        }
    }

}
