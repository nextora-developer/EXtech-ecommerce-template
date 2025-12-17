@extends('admin.layouts.app')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="px-4 py-2 rounded bg-black text-white">+ New</a>
</div>

<div class="bg-white border rounded overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="text-left text-gray-500">
            <tr>
                <th class="p-3">Name</th>
                <th class="p-3">Slug</th>
                <th class="p-3"></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($categories as $c)
            <tr class="border-t">
                <td class="p-3 font-medium">{{ $c->name }}</td>
                <td class="p-3 text-gray-600">{{ $c->slug }}</td>
                <td class="p-3 text-right">
                    <a class="underline mr-3" href="{{ route('admin.categories.edit', $c) }}">Edit</a>
                    <form class="inline" method="POST" action="{{ route('admin.categories.destroy', $c) }}"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="underline text-red-600">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
