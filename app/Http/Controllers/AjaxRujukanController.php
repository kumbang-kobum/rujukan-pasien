<?php

namespace App\Http\Controllers;

use App\Models\RumahSakit;
use App\Models\User;

class AjaxRujukanController extends Controller
{
    public function dokterByRs(RumahSakit $rs)
    {
        // hanya dokter di RS tsb
        $list = User::where('role', 'dokter')
            ->where('rumah_sakit_id', $rs->id)
            ->orderBy('name')
            ->get(['id','name']);

        return response()->json($list);
    }
}
