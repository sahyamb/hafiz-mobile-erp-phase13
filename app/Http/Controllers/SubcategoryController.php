<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Services\Audit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubcategoryController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()?->hasPermission('catalog.view'), 403);

        return view('subcategories.index', [
            'categories' => Category::query()->where('is_active', true)->orderBy('name')->get(),
            'subcategories' => Subcategory::query()->with('category')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('subcategory.manage') || $request->user()?->hasPermission('catalog.create'), 403);
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'min:2', 'max:80'],
        ]);
        $row = Subcategory::query()->create([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'is_active' => true,
            'created_by' => $request->user()->id,
        ]);
        Audit::log('subcategory_created', 'subcategory', $row->id, $row->name, [], $row->only(['category_id', 'name']));

        return back()->with('status', 'Subcategory saved.');
    }

    public function update(Request $request, Subcategory $subcategory): RedirectResponse
    {
        abort_unless($request->user()?->hasPermission('subcategory.manage') || $request->user()?->hasPermission('catalog.edit'), 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $subcategory->update([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Subcategory saved.');
    }
}
