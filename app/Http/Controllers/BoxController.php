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
use App\Models\User;
use App\Repositories\Contracts\BoxRepositoryInterface;
use Carbon\Carbon;
use http\Env\Request;
use http\Env\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BoxController extends Controller
{

    public function __construct(private readonly BoxRepositoryInterface $boxRepository)
    {
        $this->middleware("auth:sanctum");
    }

    public function index()
    {
        if (!Gate::allows('viewAny', Box::class)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            $box = Box::all();
            return $this->reply(BoxResource::collection($box));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxRequest $request)
    {
        if (!Gate::allows('create', Box::class)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            $this->boxRepository->create($request);
            return $this->success('Omborda yangi mahsulot uchun joy ajratildi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowBoxRequest $request, Box $box)
    {
        if (!Gate::authorize('view', $box)) {
            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
        } else {
            if ($request->in_storage == true) {
                return StoreBoxHistoryResource::collection($box->boxHistories()->where('in_storage', '=', true)->get());
            }
            if ($request->out_storage == true) {
                return StoreBoxHistoryResource::collection($box->boxHistories()->where('out_storage', '=', true)->get());
            }
            if ($request->returned == 1) {
                return StoreBoxHistoryResource::collection($box->boxHistories()->where('returned', '=', true)->get());
            }

            // Box da per_pc_meter uzunlikdagi matodan qancha qolganligini ko'rsatish

            $results = BoxHistory::select('box_id', 'per_pc_meter',
                \DB::raw('SUM(CASE WHEN in_storage = true THEN pc ELSE 0 END) as total_pc_in_storage'),
                \DB::raw('SUM(CASE WHEN returned = true THEN pc ELSE 0 END) as total_pc_returned'),
                \DB::raw('SUM(CASE WHEN out_storage = true THEN pc ELSE 0 END) as total_pc_out_storage')
            )
                ->where('box_id', $box->id)
                ->groupBy('box_id', 'per_pc_meter')
                ->get();

            $finalResults = $results->map(function ($result) {
                $remaining_pc = $result->total_pc_in_storage + $result->total_pc_returned - $result->total_pc_out_storage;

                return [
                    'size_material' => $result->per_pc_meter,
                    'quantity_rulon' => $remaining_pc,
                    'length' => $remaining_pc * $result->per_pc_meter
                ];
            });

                return response()->json($finalResults);

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBoxRequest $request, Box $box)
    {
//        if (!Gate::authorize('update', Box::class)) {
//            return response()->json(["Sizda bu yerga kirish uchun ruxsat yo'q"], 403);
//        } else {
//
//            $box->name = $request->get('name');
//            $box->per_liner_meter = $request->get('per_liner_meter');
//            $box->sort_by = $request->get('sort_by');
//
//            if ($request->file('image')) {
//                $box->image_path = $request->image;
//                $box->save();
//            };
//        return $this->success("$box->id box updated", $box);
//        }
    }

    public function destroy(Box $box)
    {
        Gate::authorize('delete', $box);
        $this->boxRepository->delete($box);
        return $this->success("Box $box->id deleted");
    }

    /**
     * Jarayonda qolgan material uzunligini ko'rish mwtrda
     */

//    public function workshop($id)
//    {
//        $box = Box::find($id);
//        $boxHistoryReport = $box->boxHistories->where("created_at", Carbon::now()->startOfDay())->first()->length;
//
//        if (!$box)
//        {
//            return response()->json(['error' => 'Box not found'], 404);
//        }
//        if (!$boxHistoryReport)
//        {
//            return response()->json(['error' => 'No box history found for today'], 404);
//        }
//        return $boxHistoryReport;
//    }
}
