<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\CustomHomeSection;
use App\Models\CustomHomeSectionItem;

class SectionsController extends Controller
{

    public function index()
    {
        $sections = CustomHomeSection::all();
        return view('dashboard.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('dashboard.sections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.content_id' => 'required|integer',
            'items.*.content_type' => 'required|string|in:movie,serie',
            'items.*.order' => 'nullable|integer',
        ]);

        $section = CustomHomeSection::create([
            'name' => $request->name,
            'order' => $request->order ?? 0,
            'active' => true,
        ]);

        foreach ($request->items as $item) {
            CustomHomeSectionItem::create([
                'section_id' => $section->id,
                'content_id' => $item['content_id'],
                'content_type' => $item['content_type'],
                'order' => $item['order'] ?? 0,
            ]);
        }

        return redirect()->route('sections.index')->with('success', 'Seção criada com sucesso!');
    }

    public function edit(Request $request, $id)
    {
        $section = CustomHomeSection::findOrFail($id);
        return view('dashboard.sections.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'items' => 'required|array|min:1',
            'items.*.content_id' => 'required|integer',
            'items.*.content_type' => 'required|string|in:movie,serie',
            'items.*.order' => 'nullable|integer',
        ]);

        $section = CustomHomeSection::findOrFail($id);
        $section->update([
            'name' => $request->name,
            'order' => $request->order ?? 0,
        ]);

        // Apagar os itens antigos
        $section->items()->delete();

        // Criar novamente
        foreach ($request->items as $item) {
            CustomHomeSectionItem::create([
                'section_id' => $section->id,
                'content_id' => $item['content_id'],
                'content_type' => $item['content_type'],
                'order' => $item['order'] ?? 0,
            ]);
        }

        return redirect()->route('sections.index')->with('success', 'Seção atualizada com sucesso!');
    }

    public function destroy(CustomHomeSection $section)
    {
        try {
            $section->delete();
            return redirect()->route('sections.index')->with('success', 'Seção deletada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete section.');
        }
    }
}
