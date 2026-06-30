<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VariantAttribute;
use App\Models\VariantValue;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VariantAttributeController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $attributes = VariantAttribute::all();
        $values = VariantValue::with('attribute')->get();

        return view('catalog.variants', compact('attributes', 'values'));
    }

    // Show the form for creating a new resource.
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        VariantAttribute::create($request->all());

        return back()->with('success', 'Attribute created');
    }

    // Show the form for editing the specified resource.
    public function update(Request $request, $id)
    {
        $attr = VariantAttribute::findOrFail($id);
        $attr->update($request->all());

        return back()->with('success', 'Attribute updated');
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        VariantAttribute::destroy($id);
        return back()->with('success', 'Attribute deleted');
    }

    // Show the products associated with a specific variant attribute.
    public function show($id)
    {
        $attribute = VariantAttribute::findOrFail($id);

        $products = Product::with(['variants.value', 'variants.attribute'])
        ->whereHas('variants', function ($q) use ($id) {
            $q->where('variant_attribute_id', $id);
        })
        ->get();

        $values = VariantValue::where('variant_attribute_id', $attribute->id)->get();

        $allProducts = Product::orderBy('title', 'asc')->get();

        return view('catalog.product-variant', compact('attribute', 'products', 'values', 'allProducts'));
    }

    public function updateVariant(Request $request)
    {
        if (!$request->has('variants_data') || !is_array($request->variants_data)) {
            return back()->with('error', 'No variant data sent from form.');
        }

        DB::beginTransaction();

        try {
            $productId = $request->product_id;
            $attributeId = $request->attribute_id;

            // Keep track of IDs we want to preserve
            $keepValueIds = [];

            foreach ($request->variants_data as $key => $row) {
                $valueText = isset($row['value']) ? trim($row['value']) : '';
                
                if ($valueText === '') {
                    continue; 
                }

                // CASE 1: Updating an existing variant row
                if (is_numeric($key) && isset($row['value_id']) && !empty($row['value_id'])) {
                    
                    $valueId = $row['value_id'];

                    DB::table('variant_values')
                        ->where('id', $valueId)
                        ->update([
                            'value'      => $valueText,
                            'updated_at' => now()
                        ]);

                    // Track this ID so we don't delete it later
                    $keepValueIds[] = $valueId;

                } else {
                    // CASE 2: Inserting a brand new variant row (e.g., "new_1")
                    
                    // STEP A: Insert into variant_values
                    $newValueId = DB::table('variant_values')->insertGetId([
                        'variant_attribute_id' => $attributeId,
                        'value'                => $valueText,
                        'created_at'           => now(),
                        'updated_at'           => now()
                    ]);

                    // STEP B: Link relationship to product_variants mapping table
                    DB::table('product_variants')->insert([
                        'product_id'           => $productId,
                        'variant_attribute_id' => $attributeId,
                        'variant_value_id'     => $newValueId,
                        'created_at'           => now(),
                        'updated_at'           => now()
                    ]);

                    // Track this new ID as well so it is preserved
                    $keepValueIds[] = $newValueId;
                }
            }

            // CASE 3: Handle Deletions (Frontend items removed via the '-' button)
            // Find any existing variant value IDs linked to this specific product and attribute that are NOT in our keep array
            $valueIdsToDelete = DB::table('product_variants')
                ->where('product_id', $productId)
                ->where('variant_attribute_id', $attributeId)
                ->whereNotIn('variant_value_id', $keepValueIds)
                ->pluck('variant_value_id')
                ->toArray();

            if (!empty($valueIdsToDelete)) {
                // First drop pivot connections
                DB::table('product_variants')
                    ->where('product_id', $productId)
                    ->where('variant_attribute_id', $attributeId)
                    ->whereIn('variant_value_id', $valueIdsToDelete)
                    ->delete();

                // Next clean up core variant values data table
                DB::table('variant_values')
                    ->whereIn('id', $valueIdsToDelete)
                    ->delete();
            }

            DB::commit();
            return back()->with('success', 'Variants updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }

    public function storeNewVariant(Request $request)
    {
        $request->validate([
            'product_id'   => 'required',
            'attribute_id' => 'required',
            'new_variants' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $productId = $request->product_id;
            $attributeId = $request->attribute_id;

            foreach ($request->new_variants as $valueText) {
                $valueText = trim($valueText);
                
                if ($valueText === '') {
                    continue;
                }

                // 1. Insert into variant_values with relation to its attribute type
                $valueId = DB::table('variant_values')->insertGetId([
                    'variant_attribute_id' => $attributeId,
                    'value'                => $valueText,
                    'created_at'           => now(),
                    'updated_at'           => now()
                ]);

                // 2. Link the generated value record straight to the chosen Product code mapping table
                DB::table('product_variants')->insert([
                    'product_id'           => $productId,
                    'variant_attribute_id' => $attributeId,
                    'variant_value_id'     => $valueId,
                    'created_at'           => now(),
                    'updated_at'           => now()
                ]);
            }

            DB::commit();
            return back()->with('success', 'Product variants added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }
}
