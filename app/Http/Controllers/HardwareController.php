<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HardwareController extends Controller
{
    public function verifyQr(Request $request)
    {
        Log::info('Hardware log:', $request->all());
        return response()->json(['status' => 'success', 'message' => 'Signal received'], 200);
    }
}
