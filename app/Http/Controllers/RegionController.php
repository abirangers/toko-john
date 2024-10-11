<?php

namespace App\Http\Controllers;

use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    //

    public function provinces()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function regencies($province_id)
    {
        Log::info("province_id nih $province_id");
        $regencies = Regency::where('province_id', $province_id)->get();
        Log::info("regencies nih $regencies");
        return response()->json($regencies);
    }

    public function districts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)->get();

        return response()->json($districts);
    }

    public function villages($district_id)
    {
        $villages = Village::where('district_id', $district_id)->get();

        return response()->json($villages);
    }
}