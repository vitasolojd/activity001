<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlowerController extends Controller
{
    // GET /api/flowers
    public function index(Request $request)
    {
        $flowers = Flower::query();

        if ($request->filled('name')) {
            $flowers->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('category')) {
            $flowers->where('category', 'like', '%' . $request->input('category') . '%');
        }

        return response()->json($flowers->get());
    }

    // POST /api/flowers
    public function store(Request $request)
    {
        // Merge data from both body and query parameters
        // This allows data to come from both sources
        $data = array_merge($request->all(), $request->query());

        // Validate the merged data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the flower with validated data
        $flower = Flower::create([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'image' => $data['image'] ?? null,
            'status' => $data['status'] ?? 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flower created successfully.',
            'data' => $flower
        ], 201);
    }

    // GET /api/flowers/{id}
    public function show($id)
    {
        return response()->json(Flower::findOrFail($id));
    }

    // PUT /api/flowers/{id}
    public function update(Request $request, $id)
    {
        $flower = Flower::findOrFail($id);

        // Merge data from both body and query parameters
        $data = array_merge($request->all(), $request->query());

        // Validate the merged data
        $validator = Validator::make($data, [
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'image' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Update the flower with validated data
        $flower->update([
            'name' => $data['name'] ?? $flower->name,
            'category' => $data['category'] ?? $flower->category,
            'description' => $data['description'] ?? $flower->description,
            'price' => $data['price'] ?? $flower->price,
            'stock' => $data['stock'] ?? $flower->stock,
            'image' => $data['image'] ?? $flower->image,
            'status' => $data['status'] ?? $flower->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Flower updated successfully.',
            'data' => $flower
        ]);
    }

    // DELETE /api/flowers/{id}
    public function destroy($id)
    {
        $flower = Flower::findOrFail($id);
        $flower->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flower deleted successfully.'
        ]);
    }
}