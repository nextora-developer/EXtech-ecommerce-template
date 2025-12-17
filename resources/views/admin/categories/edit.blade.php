@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Edit Category</h1>

<form method="POST" action="{{ route('admin.categories.update', $category) }}" class="bg-white border rounded p-4 max-w-xl">
    @csrf @method('PUT')

    <label class="block text-sm text-gray-600 mb-1">Name</label>
    <input name="name" value="{{ old('name', $category->name) }}" class="w-full border rounded px-3 py-2" required>

    <div class="mt-2 text-sm text-gray-500">Slug: {{ $category->slug }}</div>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded border">Back</a>
        <button class="px-4 py-2 rounded bg-black text-white">Update</button>
    </div>
</form>
@endsection
