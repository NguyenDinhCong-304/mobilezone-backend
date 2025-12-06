<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Str;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Topic::orderBy('sort_order', 'desc');

        // Nếu có tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Nếu có lọc trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }

        // Phân trang (mặc định 10 mục/trang)
        $topics = $query->paginate(10);

        return response()->json($topics);
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
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:topic,slug',
            'sort_order'  => 'nullable|integer',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['created_by'] = auth()->id() ?? 1;
        $validated['updated_by'] = auth()->id() ?? 1;

        $topic = Topic::create($validated);

        return response()->json([
            'message' => 'Thêm chủ đề thành công',
            'data' => $topic,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $topic = Topic::findOrFail($id);
        return response()->json([
            'message' => 'Chi tiết chủ đề',
            'data' => $topic
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
        $topic = Topic::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'slug'        => 'sometimes|nullable|string|max:255|unique:topic,slug,' . $id,
            'sort_order'  => 'nullable|integer',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        if (isset($validated['name']) && empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['updated_by'] = auth()->id() ?? 1;

        $topic->update($validated);

        return response()->json([
            'message' => 'Cập nhật chủ đề thành công',
            'data' => $topic,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->delete(); //Xóa mềm
        return response()->json(['message' => 'Xóa chủ đề thành công']);
    }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    public function forceDestroy(string $id)
    {
        $topic = Topic::withTrashed()->findOrFail($id);
        $topic->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa chủ đề thành công']);
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore(string $id)
    {
        $topic = Topic::withTrashed()->findOrFail($id);
        $topic->restore(); // khôi phục lại

        return response()->json(['message' => 'Khôi phục topic thành công']);
    }
}
