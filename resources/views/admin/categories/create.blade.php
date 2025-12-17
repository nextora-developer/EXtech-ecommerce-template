@extends('admin.layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">New Category</h1>

<form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white border rounded p-4 max-w-xl">
    @csrf
    <label class="block text-sm text-gray-600 mb-1">Name</label>
    <input name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>

    <div class="mt-4 flex gap-2">
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded border">Cancel</a>
        <button class="px-4 py-2 rounded bg-black text-white">Save</button>
    </div>
</form>
@endsection
