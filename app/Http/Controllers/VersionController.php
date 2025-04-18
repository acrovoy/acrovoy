<?php

namespace App\Http\Controllers;
use App\Models\Versions;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function getCurrentVersion($app, $version)
    {
        
        $current_version = Versions::where('version', $version)
        ->where('app', $app)
        ->first();
        
        return response()->json([
            'message' => 'Get version successfully',
            'data' => $current_version
        ], 200);

    }
}
