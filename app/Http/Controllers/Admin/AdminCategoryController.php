<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);

        $baseSlug = Str::slug($data['name']);
        $slug = $baseSlug;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $i++;
        }

        Category::create([
            'name' => $data['name'],
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Category created');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
        ]);

        if ($category->name !== $data['name']) {
            $baseSlug = Str::slug($data['name']);
            $slug = $baseSlug;
            $i = 2;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $category->slug = $slug;
        }

        $category->name = $data['name'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted');
    }
}
