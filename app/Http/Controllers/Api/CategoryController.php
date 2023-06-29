<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use  App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;



class CategoryController extends Controller

{
   
    public function index()
    {
    

            return CategoryResource::collection(Category::all());

    }

 
    public function store(Request $request)
    {
     

        $newCategory = Category::create($request->validated());
        return CategoryResource::make($newCategory);

    }

  
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        return CategoryResource::make($category);
    }

   
    public function update(Request $request, string $id)
    {
      

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->update($request->all());

        return CategoryResource::make($category);

    }

  
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
