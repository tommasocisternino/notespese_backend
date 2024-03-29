<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        return response()->json(["categories" => Category::where('user_id',auth()->user()->id)->get()],200);
    }

    public function create(Request $request){
        $request->validate([
            'name' => 'string|required',
            'color' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->color = $request->color;
        $category->user_id = auth()->user()->id;

        try {
            $category->save();
        }catch (\Exception $exception){
            return response()->json(["category" => null,"error" => $exception->getMessage()],500);
        }

        return response()->json(["category" => $category->refresh()],201);
    }


    public function update(Request $request){

        $category = Category::findOrFail($request->id);

        if ($category->user_id === auth()->user()->id){
            $category->name = $request->name;
            $category->color = $request->color;
            $category->save();
        }else{
            return response()->json(["message" => "Forbidden"],401);
        }

        return response()->json(["message" => "Category updated", "category" => $category],200);
    }

    public function delete(Request $request){

        $category = Category::findOrFail($request->id);

        if ($category->user_id === auth()->user()->id){
            $category->delete();
        }else{
            return response()->json(["message" => "Forbidden"],401);
        }

        return response()->json(["message" => "Category deleted"],204);
    }
}
