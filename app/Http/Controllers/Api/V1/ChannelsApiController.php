<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TVChannel;

class ChannelsApiController extends Controller {
    public function index() 
    {
        $channels = TVChannel::with('links')->orderBy('name')->get();
        return response()->json($channels);
    }

    // public function show($id) 
    // {
    //     $channel = TVChannel::findOrFail($id);
    //     return response()->json($channel);
    // }

}