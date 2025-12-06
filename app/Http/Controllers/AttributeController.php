<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Attribute::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request, $productId)
    // {
    //     $request->validate([
    //         'attribute_id' => 'required|exists:attributes,id',
    //         'value' => 'required|string|max:255',
    //     ]);

    //     $pa = ProductAttribute::create([
    //         'product_id' => $productId,
    //         'attribute_id' => $request->attribute_id,
    //         'value' => $request->value,
    //     ]);

    //     return response()->json($pa, 201);
    // }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
        ]);

        $attr = Attribute::create([
            'name' => $request->name,
        ]);

        return response()->json($attr, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function destroy($productId, $attrId)
    {
        ProductAttribute::where('product_id', $productId)
            ->where('id', $attrId)
            ->delete();

        return response()->json(null, 204);
    }
}
