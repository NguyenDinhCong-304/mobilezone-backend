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
        $mode = $request->get('mode', 'admin');

        $query = Menu::query()
            ->where('status', 1)
            ->orderBy('sort_order', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // FRONTEND
        if ($mode === 'frontend') {
            $menus = $query
                ->whereNull('parent_id')
                ->with(['children' => function ($q) {
                    $q->where('status', 1)
                    ->orderBy('sort_order', 'asc');
                }])
                ->get();

            return response()->json($menus);
        }

        // ADMIN
        return response()->json(
            $query->with('parent')->paginate(10)
        );
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
        // Chuẩn hóa dữ liệu
        $request->merge([
            'type' => strtolower(trim($request->type)),
            'status' => (int) $request->status,
            'parent_id' => $request->parent_id ?: null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string',
            'type' => 'required|in:category,topic,page,main,footer',
            'parent_id' => 'nullable|exists:menu,id',
            'table_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $menu = Menu::create([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? null,
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

        switch ($validated['type']) {
            case 'category':
                $items = Category::where('status', 1)
                    ->get(['id', 'name', 'slug']);
                $parentId = 8; // ID menu "Sản phẩm"
                $prefix = '/products/';
                break;

            case 'topic':
                $items = Topic::where('status', 1)
                    ->get(['id', 'name', 'slug']);
                //$parentId = 0;
                $prefix = '/topic/';
                break;

            case 'page':
                $items = Post::where('type', 'page')
                    ->where('status', 1)
                    ->get(['id', 'title as name', 'slug']);
                //$parentId = 0;
                $prefix = '/page/';
                break;
        }

        foreach ($items as $item) {
            Menu::updateOrCreate(
                [
                    'table_id' => $item->id,
                    'type' => $validated['type'],
                ],
                [
                    'name' => $item->name,
                    'link' => $prefix . $item->id,
                    'parent_id' => $parentId,
                    'sort_order' => 1,
                    'status' => 1,
                    'created_by' => 1,
                ]
            );
        }

        return response()->json([
            'message' => 'Đã import menu từ ' . $validated['type']
        ]);
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

        // Chuẩn hóa dữ liệu từ frontend
        $request->merge([
            'type' => strtolower(trim($request->type)),
            'status' => (int) $request->status,
            'parent_id' => $request->parent_id ?: null,
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string',
            'type' => 'required|in:category,topic,page,footer,main',
            'parent_id' => 'nullable|exists:menu,id',
            'table_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ]);

        $menu->update([
            'name' => $validated['name'],
            'link' => $validated['link'],
            'type' => $validated['type'],
            'parent_id' => $validated['parent_id'] ?? null,
            'table_id' => $validated['table_id'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 1,
            'updated_by' => $request->user_id ?? 1,
            'status' => $validated['status'],
        ]);
        //dd($request->all());
        return response()->json([
            'message' => 'Cập nhật menu thành công!',
            'menu' => $menu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $menu = Menu::withTrashed()->findOrFail($id);
        $menu->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh viễn menu thành công']);
    }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    // public function forceDestroy(string $id)
    // {
    //     $menu = Menu::withTrashed()->findOrFail($id);
    //     $menu->forceDelete(); // xóa hẳn khỏi DB

    //     return response()->json(['message' => 'Xóa vĩnh viễn menu thành công']);
    // }

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
