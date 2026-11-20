<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::withCount('properties')->orderBy('sort')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => new Category(['sort' => Category::max('sort') + 1])]);
    }

    public function store(Request $request)
    {
        Category::create($this->validated($request));

        return redirect()->route('admin.categories.index')->with('status', 'Category added.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $category->update($this->validated($request, $category));

        return redirect()->route('admin.categories.index')->with('status', 'Category saved.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('status', 'Category deleted. Its listings are now uncategorised.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $request->merge(['slug' => $data['slug']])->validate([
            'slug' => [Rule::unique('categories', 'slug')->ignore($category)],
        ], ['slug.unique' => 'A category with this name already exists.']);
        $data['sort'] = (int) ($data['sort'] ?? 0);

        return $data;
    }
}
