<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Str;

class LinkController extends Controller {

    public function index() {
        $links = auth()->user()->links;
        return view('links.index', compact('links'));
    }

    public function store(Request $request) {
        $request->validate(['original_url' => 'required|url']);
        $shortCode = Str::random(6);

        auth()->user()->links()->create([
            'original_url' => $request->original_url,
            'short_code' => $shortCode
        ]);

        return redirect()->route('links.index');
    }

    public function redirect($code) {
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('clicks');
        return redirect($link->original_url);
    }

    public function destroy(Link $link) {
        $link->delete();
        return redirect()->route('links.index');
    }
}
