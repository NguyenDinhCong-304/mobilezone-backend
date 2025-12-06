<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::orderBy('created_at', 'desc');

        // Nếu có tìm kiếm theo tên, email hoặc số điện thoại
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Phân trang (mặc định 10 bản ghi)
        $contacts = $query->paginate(10);

        return response()->json($contacts);
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
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string',
        ]);

        $contact = Contact::create([
            'user_id' => $request->user_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'content' => $validated['content'],
            'created_by' => $request->user_id,
            'status' => 0,
        ]);

        return response()->json([
            'message' => 'Gửi liên hệ thành công!',
            'contact' => $contact,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);
        return response()->json([
            'message' => 'Chi tiết liên hệ',
            'data' => $contact
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete(); //Xóa mềm
        return response()->json(['message' => 'Xóa liên hệ thành công']);
    }

    /**
     * Xóa cứng sản phẩm (vĩnh viễn)
     */
    public function forceDestroy(string $id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->forceDelete(); // xóa hẳn khỏi DB

        return response()->json(['message' => 'Xóa vĩnh viễn liên hệ thành công']);
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore(string $id)
    {
        $contact = Contact::withTrashed()->findOrFail($id);
        $contact->restore(); // khôi phục lại

        return response()->json(['message' => 'Khôi phục liên hệ thành công']);
    }

    /**
     * Trả lời liên hệ
     */
    public function reply(Request $request, string $id)
    {
        $validated = $request->validate([
            'reply_id' => 'required|exists:users,id',
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'reply_id' => $validated['reply_id'],
            'status' => 1, // Đánh dấu là đã trả lời
            'updated_by' => $validated['reply_id'],
        ]);

        return response()->json([
            'message' => 'Trả lời liên hệ thành công!',
            'contact' => $contact,
        ], 200);
    }
}
