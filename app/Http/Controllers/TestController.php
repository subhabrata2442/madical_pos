<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Brand;

class TestController extends Controller
{
    public function sqldownload(){

        $livebrands = Brand::on('live_server_connection')->get();
        dd($livebrands);
        if ($livebrands) {
            foreach ($livebrands as $livebrand) {
                $localbrand = Brand::updateOrCreate([
                    'id' => $livebrand->id,
                ], [
                    'name'          => $livebrand->name,
                    'slug'          => $livebrand->slug,
                    'deleted_at'    => $livebrand->deleted_at,
                ]);
            }
        }

    }
}
