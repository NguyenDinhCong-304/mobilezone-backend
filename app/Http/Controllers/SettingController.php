<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::all();
        return response()->json($setting);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $setting = Setting::findOrFail($id);
        return response()->json([
            'message' => 'Chi tiết website setting',
            'data' => $setting
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $setting = Setting::findOrFail($id);

        $request->validate([
            'site_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'nullable|string|max:20',
            'hotline'   => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:255',
            'status'    => 'required|in:0,1',
        ]);

        $setting->update($request->all());

        return response()->json([
            'message' => 'Cập nhật cài đặt thành công!',
            'data' => $setting,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete(); //Xóa mềm
        return response()->json(['message' => 'Xóa cài đặt thành công']);
    }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    public function forceDestroy(string $id)
    {
        $setting = Setting::withTrashed()->findOrFail($id);
        $setting->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh viễn cài đặt thành công']);
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore(string $id)
    {
        $setting = Setting::withTrashed()->findOrFail($id);
        $setting->restore(); // khôi phục lại

        return response()->json(['message' => 'Khôi phục cài đặt thành công']);
    }
}
