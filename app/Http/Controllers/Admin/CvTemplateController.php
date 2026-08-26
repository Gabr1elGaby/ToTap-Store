<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CvTemplateController extends Controller
{
    public function index()
    {
        $templates = DB::table('cv_templates')->orderBy('id')->get();
        return view('admin.cv-templates.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = DB::table('cv_templates')->where('id', $id)->first();
        if (!$template) abort(404);
        return view('admin.cv-templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'price_normal' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        DB::table('cv_templates')->where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price,
            'price_normal' => $request->price_normal,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        $cvProduct = DB::table('products')->where('slug', 'like', '%cv%')->orWhere('name', 'like', '%cv%')->first();
        if ($cvProduct) {
            return redirect()->route('admin.products.edit', $cvProduct->id)->with('success', 'Harga dan Diskon Template CV berhasil diperbarui.');
        }

        return redirect()->route('admin.cv-templates.index')->with('success', 'Harga dan Diskon Template CV berhasil diperbarui.');
    }
}
