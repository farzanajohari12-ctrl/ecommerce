<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Badge;
use App\Models\VariantAttribute;

use Illuminate\Http\Request;

class AddProductController extends Controller
{
   /**
     * Show the form to create a new product.
     */
    public function create()
    {
        // 1. Fetch collections from your database tables
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        $badges = Badge::orderBy('name', 'asc')->get();
        
        // Note: Make sure your variant model class name matches your database table setup 
        // (e.g. VariantAttribute, Variant, or Attribute)
        $variants = VariantAttribute::orderBy('name', 'asc')->get();

        // 2. Pass them down to your Blade layout view template
        // Adjust the view path 'e-commerce.add-product' if your file path structure is different
        return view('e-commerce.add-product', compact('categories', 'tags', 'badges', 'variants'));
    }

    public function store(Request $request)
    {
        // You can implement saving here or just return back for now
        return redirect()->route('add-product.create')->with('success', 'Product added (simulated).');
    }
}
