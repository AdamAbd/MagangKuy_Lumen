<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HasFactory;

    public function index()
    {
        $data = Category::get();

        return response()->json($data);
    }

    public function create(Request $request)
    {
        try {
            $category = new Category;
            $category->name = $request->name;

            if ($category->save()) {
                $data = Category::where('name', $request->name)->orderBy('created_at', 'desc')->first();
                return response()->json(['massage' => 'Success', 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $gambar = $request->file('picture_path')->getClientOriginalName();

            $generateNum = random_int(1, 1000);
            $newName = "$generateNum.$request->name.$gambar";

            $request->file('picture_path')->move('storage/categories', $newName);

            $category->name = $request->name;
            $category->picture_path = 'storage/categories/' . $newName;

            if ($category->save()) {
                $data = Category::where('name', $request->name)->orderBy('created_at', 'desc')->first();
                return response()->json(['massage' => 'Success', 'data' => $data]);
            }
        } catch (\Exception $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $data = Category::where('id', $id)->get();

        return response()->json(['message' => 'Success', 'data' => $data]);
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            if ($category->delete()) {
                return response()->json(['massage' => 'Success', 'data' => null]);
            }
        } catch (\Throwable $e) {
            return response()->json(['massage' => 'Failed', 'data' => $e->getMessage()], 400);
        }
    }
}
