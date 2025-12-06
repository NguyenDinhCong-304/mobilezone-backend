<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Post;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Menu::query()->orderBy('sort_order', 'desc');

        // Nếu có lọc theo loại (category, topic, page, custom)
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Nếu có lọc theo trạng thái
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $menus = $query->get();

        return response()->json($menus);
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
            'name' => 'required|string|max:255',
            'link' => 'required|string',
            'type' => 'required|in:category,topic,page,custom',
            'parent_id' => 'nullable|integer',
            'table_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? 0,
            'table_id' => $validated['table_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 1,
            'created_by' => $request->user_id ?? 1,
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Thêm menu thành công!',
            'menu' => $menu
        ], 201);
    }

    /**
     * Import menu items from categories, topics, or pages.
     */

    public function importFromSource(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:category,topic,page',
        ]);

        $model = null;
        switch ($validated['type']) {
            case 'category':
                $model = Category::where('status', 1)->get(['id', 'name']);
                break;
            case 'topic':
                $model = Topic::where('status', 1)->get(['id', 'name']);
                break;
            case 'page':
                $model = Post::where('type', 'page')->where('status', 1)->get(['id', 'title as name']);
                break;
        }

        foreach ($model as $item) {
            Menu::updateOrCreate(
                ['table_id' => $item->id, 'type' => $validated['type']],
                [
                    'name' => $item->name,
                    'link' => '/' . $validated['type'] . '/' . $item->id,
                    'parent_id' => 0,
                    'sort_order' => 1,
                    'created_by' => 1,
                    'status' => 1,
                ]
            );
        }

        return response()->json(['message' => 'Đã thêm menu từ ' . $validated['type']]);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json([
            'message' => 'Chi tiết menu',
            'data' => $menu
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
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string',
            'type' => 'required|in:category,topic,page,custom',
            'parent_id' => 'nullable|integer',
            'table_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
            'status' => 'required|in:0,1',
        ]);

        $menu->update([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? 0,
            'table_id' => $validated['table_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 1,
            'updated_by' => $request->user_id ?? 1,
            'status' => $validated['status'],
        ]);

        return response()->json(['message' => 'Cập nhật menu thành công!', 'menu' => $menu]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete(); //Xóa mềm
        return response()->json(['message' => 'Xóa menu thành công']);
    }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    public function forceDestroy(string $id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh viễn menu thành công']);
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore(string $id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->restore(); // khôi phục lại

        return response()->json(['message' => 'Khôi phục menu thành công']);
    }
}
