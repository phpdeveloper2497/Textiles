<?php

namespace App\Repositories\Eloquent;

use App\Http\Requests\StoreBoxRequest;
use App\Models\Box;
use App\Repositories\Contracts\BoxRepositoryInterface;

//use Your Model

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
    public function  delete()
    {
        // TODO: Implement delete() method.
    }

    public function show()
    {

    }

}
