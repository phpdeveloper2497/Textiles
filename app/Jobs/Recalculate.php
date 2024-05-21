<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Recalculate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $box;

    /**
     * Create a new job instance.
     */
    public function __construct($box)
    {
        $this->box = $box;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        $boxes = [];
//        foreach ($boxes as $box) {
//            $outgoing_materials = $box->history()->where('out_storage', '=', true)->sum('length');
//            $plan_amount = $outgoing_materials / $box->per_liner_meter;
//            $handkerchiefs_finish = $box->handkerchief()->handkerchiefHistory()->where("storage_in", "=", true)->sum('finished_products');
//            $handkerchiefs_defect= $box->handkerchief()->handkerchiefHistory()->where("storage_in", "=", true)->sum('defective_products');
//            $all_finished_products = $handkerchiefs_finish + $handkerchiefs_defect;
//            $in_progress = $plan_amount - $all_finished_products;
//            $in_progress_material = $in_progress * $box->per_liner_meter;
//        }
//
//        $current_time = Carbon::now();
//        $target_time_end_day = Carbon::today()->setHour(23)->setMinute(30)->setSecond(0);
//        $target_time_start_day = Carbon::today()->setHour(7)->setMinute(00)->setSecond(0);
//        if ($current_time >= $target_time_end_day && $current_time < $target_time_start_day) {
//            Box::query()->where('id', '=', $request->box_id)->first()->increment('remainder',  $in_progress_material);
//        }
//        if ($current_time = $target_time_start_day) {
//            Box::query()->where('id', '=', $request->box_id)->first()->decrement('remainder',  $in_progress_material);
//
//        }
    }
}

