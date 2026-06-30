<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Badge;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\VariantAttribute;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // List all products with pagination
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('e-commerce.index', compact('products'));
    }

    // Store new product with multiple images, tags, badges, and variants
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock_quantity' => 'required|integer',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // 1. CREATE PRODUCT
        $product = Product::create([
            'title'          => $request->title,
            'description'    => $request->description,
            'price'          => $request->price,
            'sale_price'     => $request->sale_price,
            'stock_quantity' => $request->stock_quantity,
            'category_id'    => $request->category_id,
        ]);

        // 2. SAVE IMAGES
        if ($request->hasFile('images') && is_array($request->file('images'))) {

            $destination = public_path('img/products');

            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            foreach ($request->file('images') as $file) {

                if (!$file->isValid()) continue;

                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $destination = storage_path('app/public/products');

                if (!file_exists($destination)) {
                    mkdir($destination, 0777, true);
                }

                foreach ($request->file('images') as $file) {

                    if (!$file->isValid()) continue;

                    $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    $file->move($destination, $imageName);

                    $publicUrl = url('storage/products/' . $imageName);

                    DB::table('product_images')->insert([
                        'product_id' => $product->id,
                        'image_path' => $publicUrl, // 🔥 IMPORTANT CHANGE
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // 3. SAVE TAGS
        if ($request->tags) {
            $product->tags()->attach($request->tags);
        }

        // 4. SAVE BADGES
        if ($request->badges) {
            $product->badges()->attach($request->badges);
        }

        // 5. SAVE VARIANTS
        if ($request->variant_option && $request->variant_value) {

            foreach ($request->variant_option as $key => $attributeId) {

                $values = explode(',', $request->variant_value[$key]);

                foreach ($values as $val) {

                    $val = trim($val);
                    if (!$val) continue;

                    // 🔥 find or create value
                    $variantValue = DB::table('variant_values')
                        ->where('variant_attribute_id', $attributeId)
                        ->where('value', $val)
                        ->first();

                    if (!$variantValue) {
                        $variantValueId = DB::table('variant_values')->insertGetId([
                            'variant_attribute_id' => $attributeId,
                            'value' => $val,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        $variantValueId = $variantValue->id;
                    }

                    // insert product variant
                    DB::table('product_variants')->insert([
                        'product_id' => $product->id,
                        'variant_attribute_id' => $attributeId,
                        'variant_value_id' => $variantValueId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Product created successfully');
    }

    // Delete product and its associated images, tags, badges, and variants
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    // View product details with all relationships
    public function view($id)
    {
        $product = Product::with([
            'images',
            'tags',
            'badges',
            'variants.attribute',
            'variants.value',
            'category'
        ])->findOrFail($id);

        $categories = Category::all();
        $allTags = Tag::all();
        $allBadges = Badge::all();
        $attributes = VariantAttribute::with('values')->get();

        return view('e-commerce.view', compact(
            'product',
            'categories',
            'allTags',
            'allBadges',
            'attributes'
        ));
    }

    // Update product details, manage images, tags, badges, and variants
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {

            // =========================
            // 1. FIND PRODUCT
            // =========================
            $product = Product::findOrFail($request->product_id);

            // =========================
            // 2. UPDATE MAIN PRODUCT
            // =========================
            $product->title = $request->title;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->sale_price = $request->sale_price;
            $product->category_id = $request->category_id;
            $product->save();

            // =========================
            // 3. DELETE SELECTED IMAGES
            // =========================
            if (!empty($request->delete_images)) {

                foreach ($request->delete_images as $imageId) {

                    $image = ProductImage::find($imageId);

                    if ($image) {

                        $filePath = public_path($image->image_path);

                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }

                        $image->delete();
                    }
                }
            }

            // =========================
            // 4. UPLOAD NEW IMAGES (MAX 4 LIMIT)
            // =========================

            $existingImageCount = $product->images()->count();

            if ($request->hasFile('images')) {

                $newImages = $request->file('images');

                // total after upload
                $totalImages = $existingImageCount + count($newImages);

                if ($totalImages > 4) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum 4 images allowed only.'
                    ], 422);
                }

                $path = public_path('img/products');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                foreach ($newImages as $file) {

                    if (!$file->isValid()) {
                        continue;
                    }

                    $filename = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();

                    $file->move($path, $filename);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'img/products/' . $filename,
                    ]);
                }
            }

            // =========================
            // 5. UPDATE TAGS (SELECT2 ARRAY FIX)
            // =========================
            $tagIds = [];

            $tags = $request->tags ?? [];

            foreach ($tags as $tagName) {
                $tagName = trim($tagName);

                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }

            $product->tags()->sync($tagIds);


            // =========================
            // 6. UPDATE BADGES (SELECT2 ARRAY FIX)
            // =========================
            $badgeIds = [];

            $badges = $request->badges ?? [];

            foreach ($badges as $badgeName) {
                $badgeName = trim($badgeName);

                if (!empty($badgeName)) {
                    $badge = Badge::firstOrCreate(['name' => $badgeName]);
                    $badgeIds[] = $badge->id;
                }
            }

            $product->badges()->sync($badgeIds);

            // =========================
            // 7. COMMIT
            // =========================
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!'
            ]);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX action: Save a category from the popup modal.
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json($category); // IMPORTANT for AJAX
    }

    /**
     * AJAX action: Save a tag from the popup modal.
     */
    public function storeTag(Request $request)
    {
        $tag = Tag::create([
            'name' => $request->name
        ]);

        return response()->json($tag);
    }

    /**
     * AJAX action: Save a badge from the popup modal.
     */
    public function storeBadge(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:badges,name']);
        $badge = Badge::create(['name' => $request->name]);
        return response()->json(['id' => $badge->id, 'name' => $badge->name]);
    }

    /**
     * AJAX action: Save a variant attribute type from the popup modal.
     */
    public function storeVariantAttribute(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:variant_attributes,name'
        ]);

        $variant = VariantAttribute::create([
            'name' => $request->name
        ]);

        return response()->json([
            'id' => $variant->id,
            'name' => $variant->name
        ]);
    }

    // Toggle product status between active (1) and inactive (0)
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);

        // toggle 1 ↔ 0
        $product->status = !$product->status;
        $product->save();

        return redirect()->back()->with('success', 'Product status updated successfully');
    }

    // Admin view to list products sorted by stock quantity (low to high)
    public function stockList()
    {
        $products = Product::orderBy('stock_quantity', 'asc')->get();

        return view('e-commerce.stock', compact('products'));
    }

    // Update stock quantity for a product (AJAX)
    public function updateStock(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $product->stock_quantity = $request->stock_quantity;
        $product->save();

        return response()->json([
            'success' => true,
            'stock' => $product->stock_quantity
        ]);
    }
}
