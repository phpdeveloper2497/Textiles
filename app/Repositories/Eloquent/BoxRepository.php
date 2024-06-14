<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\StoreBoxRequest;
use App\Models\Box;
use App\Repositories\Contracts\BoxRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;


/**
 * Class BoxRepository.
 */
class BoxRepository implements BoxRepositoryInterface
{
    public function create(StoreBoxRequest $request)
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

    }
    public function  delete(Box $box)
    {
        Storage::disk('public')->delete("$box->image_path");
        File::DeleteDirectory('storage/' . 'boxes/' . "$box->id");
        $box->delete();
    }

    public function show()
    {

    }

    public function update(StoreBoxRequest $request, Box $box){
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
    }

}
