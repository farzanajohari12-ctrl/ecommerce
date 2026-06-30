<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('catalog.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        Category::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, $id)
    {
        Category::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Category updated.');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return back()->with('success', 'Category deleted.');
    }
}
