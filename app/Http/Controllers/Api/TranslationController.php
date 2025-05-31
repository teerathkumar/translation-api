<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TranslationResource;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    public function index()
    {
        return TranslationResource::collection(Translation::paginate(50));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'locale' => 'required|string|max:5',
            'key' => 'required|string',
            'content' => 'required|string',
            'tag' => 'nullable|string',
        ]);
        return new TranslationResource(Translation::create($data));
    }

    public function show(Translation $translation)
    {
        return new TranslationResource($translation);
    }

    public function update(Request $request, Translation $translation)
    {
        $translation->update($request->validate([
            'locale' => 'sometimes|string|max:5',
            'key' => 'sometimes|string',
            'content' => 'sometimes|string',
            'tag' => 'nullable|string',
        ]));
        return new TranslationResource($translation);
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {

        $key = 'search_' . md5(json_encode($request->all()));

        return Cache::remember($key, 180, function () use ($request) {
            $query = Translation::query();
    
            if ($request->filled('locale')) $query->where('locale', $request->locale);
            if ($request->filled('key')) $query->where('key', 'like', "%{$request->key}%");
            if ($request->filled('content')) $query->where('content', 'like', "%{$request->content}%");
            if ($request->filled('tag')) $query->where('tag', $request->tag);
    
            return TranslationResource::collection($query->paginate(50));
        });


        // $query = Translation::query();
        // if ($request->filled('locale')) $query->where('locale', $request->locale);
        // if ($request->filled('key')) $query->where('key', 'like', "%{$request->key}%");
        // if ($request->filled('content')) $query->where('content', 'like', "%{$request->content}%");
        // if ($request->filled('tag')) $query->where('tag', $request->tag);

        // return TranslationResource::collection($query->paginate(50));
    }

    public function export($locale)
    {

        $cacheKey = "export_{$locale}";

        $translations = Cache::remember($cacheKey, 300, function () use ($locale) {
            return Translation::where('locale', $locale)->pluck('content', 'key');
        });
    
        return response()->json($translations);        
    }
}
