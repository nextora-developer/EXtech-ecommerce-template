<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($keyword = $request->keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('link_url', 'like', "%{$keyword}%");
            });
        }

        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

        $banners = $query->orderBy('sort_order')->latest('id')->paginate(10);

        return view('admin.banners.index', compact('banners'));
    }


    public function create()
    {
        $banner = new Banner(); // 空的，用来给 form 判断 exists 等

        return view('admin.banners.form', compact('banner'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'required|image|max:4096',
            'link_url'   => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'      => $request->title,
            'image_path' => $path,
            'link_url'   => $request->link_url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'nullable|image|max:4096',
            'link_url'   => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->only('title', 'link_url', 'sort_order');
        $data['is_active'] = $request->boolean('is_active');

        // replace image if new uploaded
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path && str_starts_with($banner->image_path, 'banners/')) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted.');
    }
}
