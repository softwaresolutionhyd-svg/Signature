<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\InventoryDepartment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = InventoryDepartment::query()
            ->withCount('products')
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->paginate(Setting::pageSize('inventory_departments_per_page', 20));

        return view('inventory.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('inventory.departments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', 'unique:tenant.inventory_departments,name'],
            'active' => ['nullable', 'boolean'],
        ]);
        $data['active'] = (bool) ($data['active'] ?? false);

        InventoryDepartment::create($data);

        return redirect()->route('inventory.departments.index')->with('status', 'Department created.');
    }

    public function edit(InventoryDepartment $department)
    {
        return view('inventory.departments.edit', compact('department'));
    }

    public function update(Request $request, InventoryDepartment $department)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('tenant.inventory_departments', 'name')->ignore($department->id)],
            'active' => ['nullable', 'boolean'],
        ]);
        $data['active'] = (bool) ($data['active'] ?? false);

        $department->update($data);

        return redirect()->route('inventory.departments.index')->with('status', 'Department updated.');
    }

    public function destroy(InventoryDepartment $department)
    {
        $department->delete();

        return redirect()->route('inventory.departments.index')->with('status', 'Department deleted.');
    }
}
