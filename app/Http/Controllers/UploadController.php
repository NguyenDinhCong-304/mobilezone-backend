<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function summernote(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('summernote', 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'No file'], 400);
    }
}
