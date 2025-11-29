<?php

namespace App\Http\Controllers\PerfomanceAndMonitoring;

use App\Http\Controllers\Controller;
use App\Models\PerfomanceAndMonitoring\InfoNeedCategory;
use App\Models\PerfomanceAndMonitoring\InfoNeed;
use App\Http\Resources\InfoNeedCategoryResource;
use App\Http\Resources\InfoNeedResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class InfoNeedsController extends Controller
{

    
    // optionally return flattened list of info needs
    public function fetchInfoNeedsCategories()
    {
        $categories = InfoNeedCategory::orderBy('name')->get();
        return response()->json(compact('categories'), 200);
    }

    public function storeInfoNeedsCategories(Request $request)
    {
        $validate = $request->validate([
            'names' => 'required|array'
        ]);
        $names = $validate['names'];
        foreach ($names as $name) {
            InfoNeedCategory::firstOrcreate([
                'name' => $name,
                'slug' => Str::slug($name)
            ]);
        }
        return 'success';
    }

    public function updateInfoNeedsCategory(Request $request, InfoNeedCategory $category)
    {
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();
        return 'updated';
    }

    public function destroyInfoNeedsCategory(Request $request, InfoNeedCategory $category)
    {
        $category->delete();
        return 'deleted';
    }

    public function fetchInfoNeeds(Request $request)
    {
        $category_id = $request->info_need_category_id;
        $info_needs = InfoNeed::where('info_need_category_id', $category_id)->orderBy('name')->get();
        return response()->json(compact('info_needs'), 200);
    }

    public function storeInfoNeeds(Request $request)
    {
        $validate = $request->validate([
            'info_need_category_id' => 'required|integer|exists:pam.info_need_categories,id',
            'names' => 'required|array'
        ]);
        $cat= InfoNeedCategory::find($validate['info_need_category_id']);
        $names = $validate['names'];
        foreach ($names as $name) {
            InfoNeed::firstOrcreate([
                'info_need_category_id' => $validate['info_need_category_id'],
                'name' => $name,
                'code' => strtoupper(Str::slug($cat->slug . '-' . $name, '_')),
                
            ]);
        }
        return 'success';
    }

    public function updateInfoNeed(Request $request, InfoNeed $infoNeed)
    {
        $cat= InfoNeedCategory::find($infoNeed->info_need_category_id);
        $infoNeed->name = $request->name;
        $infoNeed->code = strtoupper(Str::slug($cat->slug . '-' . $request->name, '_'));
        $infoNeed->save();
        return 'updated';
    }

    public function destroyInfoNeed(Request $request, InfoNeed $infoNeed)
    {
        $infoNeed->delete();
        return 'deleted';
    }


    // return categories with info needs — cached for performance
    public function index(Request $request)
    {
        $cacheKey = 'info_need_categories_with_needs_v1';
        $items = Cache::remember($cacheKey, 3600, function () {
            return InfoNeedCategory::with('infoNeeds')->orderBy('name')->get();
        });

        return InfoNeedCategoryResource::collection($items);
    }


    public function infoNeeds(Request $request)
    {
        $query = InfoNeed::query()->with('category');
        if ($request->filled('q')) {
            $query->where('name','like','%'.$request->q.'%');
        }
        $list = $query->orderBy('name')->paginate(30);
        return InfoNeedResource::collection($list)->response();
    }
}