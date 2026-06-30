<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->get();
        return view('catalog.tags', compact('tags'));
    }

    public function store(Request $request)
    {
        Tag::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Tag added.');
    }

    public function update(Request $request, $id)
    {
        Tag::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Tag updated.');
    }

    public function destroy($id)
    {
        Tag::findOrFail($id)->delete();

        return back()->with('success', 'Tag deleted.');
    }
}
