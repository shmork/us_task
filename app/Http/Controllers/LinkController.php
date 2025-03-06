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

        do {
            try {
                $shortCode = Str::random(6);
                $link = Link::create([
                    'original_url' => $request->original_url,
                    'short_code' => $shortCode,
                    'user_id' => auth()->id(),
                ]);
                break;
            } catch (QueryException $e) {
                if ($e->errorInfo[1] !== 1062) { // MySQL 1062 - Duplicate entry
                    throw $e;
                }
            }
        } while (true);

        return response()->json([
            'id' => $link->id,
            'short_url' => route('links.redirect', ['code' => $link->short_code])
        ]);
    }

    public function redirect($code) {
        $link = Link::where('short_code', $code)->firstOrFail();
        $link->increment('clicks');

        return redirect($link->original_url);
    }

    public function destroy(Link $link) {
        if ($link->user_id === auth()->id()) {
            $link->delete();
            return response()->json(['message' => 'Link deleted']);
        }

        return response()->json(['error' => 'Access denied'], 403);
    }

    public function getClicks()
    {
        $links = Link::where('user_id', auth()->id())->get(['id', 'clicks']);
        return response()->json($links);
    }

    protected function getMetaData($url)
    {
        try {
            $client = new Client();
            $response = $client->get($url);

            // Получаем содержимое страницы
            $html = $response->getBody()->getContents();

            // Используем DOMDocument для парсинга HTML
            $doc = new \DOMDocument();
            @$doc->loadHTML($html);

            $metaData = [
                'title' => '',
                'description' => '',
                'image' => '',
            ];

            $metas = $doc->getElementsByTagName('meta');
            foreach ($metas as $meta) {
                if ($meta->getAttribute('property') == 'og:title' || $meta->getAttribute('name') == 'title') {
                    $metaData['title'] = $meta->getAttribute('content');
                }

                if ($meta->getAttribute('property') == 'og:description' || $meta->getAttribute('name') == 'description') {
                    $metaData['description'] = $meta->getAttribute('content');
                }

                if ($meta->getAttribute('property') == 'og:image' || $meta->getAttribute('name') == 'image') {
                    $metaData['image'] = $meta->getAttribute('content');
                }
            }

            return $metaData;
        } catch (\Exception $e) {
            return null; // Возвращаем null, если ошибка при получении данных
        }
    }

}
