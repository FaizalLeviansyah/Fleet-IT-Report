<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use Carbon\Carbon;

class AssetController extends Controller
{
    public function index(Request $request)
{
    $selectedCategory = $request->get('category', 'Computers');
    $categories = \App\Models\AssetCategory::all();

    $assets = Asset::with(['vessel', 'location'])
        ->whereHas('category', function($q) use ($selectedCategory) {
            $q->where('name', $selectedCategory);
        })
        ->orderBy('last_seen', 'desc')
        ->get();

    return view('assets.index', compact('assets', 'categories', 'selectedCategory'));
}
}
