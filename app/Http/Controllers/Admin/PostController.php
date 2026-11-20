<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', ['posts' => Post::latest('published_at')->paginate(20)]);
    }

    public function create()
    {
        return view('admin.posts.form', ['post' => new Post(['is_published' => true, 'published_at' => now()])]);
    }

    public function store(Request $request)
    {
        Post::create($this->validated($request, new Post));

        return redirect()->route('admin.posts.index')->with('status', 'Article published.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request, $post));

        return redirect()->route('admin.posts.index')->with('status', 'Article saved.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('status', 'Article deleted.');
    }

    private function validated(Request $request, Post $post): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'slug' => ['nullable', 'alpha_dash', 'max:180', Rule::unique('posts', 'slug')->ignore($post)],
            'excerpt' => ['nullable', 'string', 'max:300'],
            'body' => ['required', 'string', 'max:50000'],
            'published_at' => ['nullable', 'date'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ]);
        $data['slug'] = ($data['slug'] ?? null) ?: ($post->slug ?: $this->uniqueSlug($data['title']));
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] ??= now();

        if ($request->hasFile('cover')) {
            if ($post->cover_path) {
                Storage::disk('public')->delete($post->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('posts', 'public');
        }
        unset($data['cover']);

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'article';
        $slug = $base;
        for ($i = 2; Post::where('slug', $slug)->exists(); $i++) {
            $slug = $base.'-'.$i;
        }

        return $slug;
    }
}
