<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query()->orderBy('sort_order', 'desc');

        // Tìm kiếm theo tên
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) { // chỉ lọc khi có giá trị thực sự
            $query->where('status', $request->status);
        }


        // Phân trang
        $banners = $query->paginate(10);

        return response()->json($banners);
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
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);

        $data = $request->only([
            'name', 'link', 'position', 'sort_order', 'description', 'status'
        ]);

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        // Nếu có user đăng nhập (ví dụ sử dụng Sanctum)
        $data['created_by'] = auth()->id() ?? 1; // hoặc null nếu bạn cho phép null
        $data['updated_by'] = auth()->id() ?? 1;

        $banner = Banner::create($data);

        return response()->json([
            'message' => 'Thêm banner thành công',
            'banner' => $banner,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        return response()->json($banner);
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
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:0,1'
        ]);

        $data = $request->all();

        // Nếu có upload ảnh mới → lưu và xoá ảnh cũ
        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        } else {
            unset($data['image']); // không ghi đè ảnh cũ
        }

        $banner->update($data);

        return response()->json([
            'message' => 'Cập nhật banner thành công',
            'banner' => $banner
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $banner = Banner::findOrFail($id);
        // $banner->delete(); //Xóa mềm
        // return response()->json(['message' => 'Xóa Banner thành công']);

        $banner = Banner::withTrashed()->findOrFail($id);
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->forceDelete();
        return response()->json(['message' => 'Xóa vĩnh viễn banner thành công']);
    }
}
