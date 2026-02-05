<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/Types/Index', [
            'types' => $types,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Types/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Type::create($data);
        return redirect()->route('admin.types.index')->with('success', 'Type created.');
    }

    public function edit($id)
    {
        $type = Type::findOrFail($id);
        return Inertia::render('Admin/Types/Edit', [
            'type' => $type,
        ]);
    }

    public function update(Request $request, Type $type)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $type->update($data);
        return redirect()->route('admin.types.index')->with('success', 'Type updated.');
    }

    public function destroy(Type $type)
    {
        $type->delete();
        return redirect()->route('admin.types.index')->with('success', 'Type deleted.');
    }
}
