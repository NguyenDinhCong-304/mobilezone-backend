<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['topic']);

        // Nếu có tìm kiếm
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', "%{$request->search}%");
        }

        // Lọc theo topic_id
        if ($request->filled('topic_id')) {
            $query->where('topic_id', $request->topic_id);
        }

        // Nếu có lọc trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        // Phân trang (mặc định 10 mục/trang)
        $posts = $query->orderBy('id', 'desc')->paginate(10);
        
        return response()->json($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic_id'    => 'required|exists:topic,id',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $post = new Post();
        $post->topic_id = $request->topic_id;
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->description = $request->description ?? '';
        $post->created_by = auth()->id() ?? 1;
        $post->status = $request->status;

        // Upload hình ảnh nếu có
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return response()->json([
            'message' => 'Thêm bài viết thành công!',
            'data' => $post
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('topic')->findOrFail($id);
        return response()->json([
            'message' => 'Chi tiết bài viết',
            'data' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'topic_id'    => 'required|exists:topic,id',
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'      => 'required|in:0,1',
        ]);

        $post->topic_id = $request->topic_id;
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->description = $request->description ?? '';
        $post->updated_by = auth()->id() ?? 1;
        $post->status = $request->status;

        // Nếu có upload ảnh mới thì xóa ảnh cũ và lưu ảnh mới
        if ($request->hasFile('image')) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('uploads/posts', 'public');
            $post->image = $path;
        }

        $post->save();

        return response()->json([
            'message' => 'Cập nhật bài viết thành công!',
            'data' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::withTrashed()->findOrFail($id);

        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->forceDelete();

        return response()->json(['message' => 'Xóa bài viết thành công']);
    }

    // /**
    //  * Xóa cứng sản phẩm (vĩnh viễn)
    //  */
    // public function forceDestroy(string $id)
    // {
    //     $post = Post::withTrashed()->findOrFail($id);
    //     $post->forceDelete(); // xóa hẳn khỏi DB

    //     return response()->json(['message' => 'Xóa vĩnh viễn post thành công']);
    // }

    // /**
    //  * Khôi phục sản phẩm đã xóa mềm
    //  */
    // public function restore(string $id)
    // {
    //     $post = Post::withTrashed()->findOrFail($id);
    //     $post->restore(); // khôi phục lại

    //     return response()->json(['message' => 'Khôi phục post thành công']);
    // }
     /**
     * Lấy danh sách bài viết mới nhất
     */
    public function post_new(Request $request)
    {
        $limit = $request->get('limit', 10);

        $posts = Post::with('topic') // load luôn thông tin chủ đề
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'message' => 'Danh sách bài viết mới',
            'count'   => $posts->count(),
            'data'    => $posts
        ]);
    }
}
