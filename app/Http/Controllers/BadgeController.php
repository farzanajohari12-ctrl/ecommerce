<?php

namespace App\Http\Controllers;
use App\Models\Badge;

use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::latest()->get();
        return view('catalog.badges', compact('badges'));
    }

    public function store(Request $request)
    {
        Badge::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Badge added.');
    }

    public function update(Request $request, $id)
    {
        Badge::findOrFail($id)->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Badge updated.');
    }

    public function destroy($id)
    {
        Badge::findOrFail($id)->delete();

        return back()->with('success', 'Badge deleted.');
    }
}
