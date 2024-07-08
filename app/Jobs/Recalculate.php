<?php

namespace App\Jobs;

use App\Models\Box;
use App\Models\BoxHistory;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Recalculate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $boxes = Box::all();

        foreach ($boxes as $box) {
            $outgoing_materials = $box->boxHistories->where('out_storage', '=', true)->sum('length');
            $plan_amount = $outgoing_materials * $box->per_liner_meter;
            $handkerchiefs_all = $box->handkerchief->sum('all_products');
//            $handkerchiefs_finish = $box->handkerchief->handkerchiefHistories()->where("sold_out", "=", true)->sum('sold_products');
//            $handkerchiefs_defect = $box->handkerchief->handkerchiefHistories()->where("sold_out", "=", true)->sum('sold_defective_products');
//            $all_finished_products = $handkerchiefs_all + $handkerchiefs_finish + $handkerchiefs_defect;
//            $all_finished_products = $handkerchiefs_finish + $handkerchiefs_defect;

            $in_progress = $plan_amount - $handkerchiefs_all;
            $in_progress_material = $in_progress / $box->per_liner_meter;
            // if(abs($in_progress) > 5 dona ){}
            if (abs($in_progress_material) > 1 ){
                $boxhistory = BoxHistory::create([
                    "box_id" => $box->id,
                    "user_id" => auth()->user(),
                    "in_storage" => 0,
                    "out_storage" => 0,
                    "returned" => 1,
                    "per_pc_meter" => 0,
                    "pc" => 0,
                    "length" => $in_progress_material,
                    "commentary" => 'Sexda qolgan material skladga keltirildi'
                ]);

                $boxhistory = BoxHistory::create([
                    "box_id" => $box->id,
                    "user_id" => auth()->user(),
                    "in_storage" => 0,
                    "out_storage" => 1,
                    "returned" => 0,
                    "per_pc_meter" => 0,
                    "pc" => 0,
                    "length" => $in_progress_material,
                    "commentary" => 'Sexga ish tugaganda kelgan material qaytib sexga chiqarib yuborildi',
                    'created_at' => Carbon::tomorrow(),
                    'updated_at' => Carbon::tomorrow()
                ]);
            }
        }

    }
}

