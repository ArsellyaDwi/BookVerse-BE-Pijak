<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('is_published', true);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $perPage = $request->get('per_page', 10);
        $blogs = $query->orderBy('published_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $blogs->items(),
            'pagination' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total()
            ]
        ]);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $blog->increment('views');

        return response()->json([
            'success' => true,
            'data' => $blog
        ]);
    }

    public function categories()
    {
        $categories = Blog::where('is_published', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
