<?php

namespace App\Http\Controllers\Policy;

use App\Http\Controllers\Controller;
use App\Models\Policy\PolicyCategory;
use App\Http\Resources\PolicyCategoryResource;
use App\Http\Resources\PolicyCategoryCollection;
use Illuminate\Http\Request;

class PolicyCategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api');
    //     $this->authorizeResource(PolicyCategory::class, 'category');
    // }

    /**
     * Display a listing of the categories.
     *
     * @return \App\Http\Resources\PolicyCategoryCollection
     */
    public function index(Request $request)
    {
        // $client_id = $request->client_id;
        $client_id = $this->getClient()->id;
        $categories = PolicyCategory::withCount('policies')->where('client_id', $client_id)->get();
        return new PolicyCategoryCollection($categories);
    }

    /**
     * Store a newly created category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\PolicyCategoryResource
     */
    public function store(Request $request)
    {
        $client_id = $this->getClient()->id;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = PolicyCategory::firstOrCreate(['client_id' => $client_id, 'name' => $validated['name']], $validated);

        return new PolicyCategoryResource($category);
    }

    /**
     * Display the specified category.
     *
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return \App\Http\Resources\PolicyCategoryResource
     */
    public function show(PolicyCategory $category)
    {
        $category->loadCount('policies');
        return new PolicyCategoryResource($category);
    }

    /**
     * Update the specified category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return \App\Http\Resources\PolicyCategoryResource
     */
    public function update(Request $request, PolicyCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return new PolicyCategoryResource($category);
    }

    /**
     * Remove the specified category.
     *
     * @param  \App\Models\Policy\PolicyCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(PolicyCategory $category)
    {
        // Check if category has policies
        if ($category->policies()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with associated policies'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}