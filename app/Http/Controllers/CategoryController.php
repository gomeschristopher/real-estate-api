<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $category = $this->category->paginate('10');

        return response()->json($category, 200);
    }

    public function store(Request $request)
    {
        try {
            return response()->json($this->category->create($request->all()), 201);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }

    public function show($id)
    {
        try {
            return response()->json($this->category->findOrFail($id), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            return response()->json($this->category->findOrFail($id)->update($request->all()), 204);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            return response()->json($this->category->findOrFail($id)->delete(), 200);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }

    public function realStates($id)
    {
        try {
            return response()->json($this->category->findOrFail($id)->realStates);
        } catch (\Exception $e) {
            return response()->json([
                "error" => $e->getMessage()
            ], 401);
        }
    }
}
