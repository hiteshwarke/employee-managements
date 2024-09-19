<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::query();
        
        if ($request->has('search')) {
            $query->where('employee_name', 'like', '%'.$request->search.'%');
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_name' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|numeric',
            'dob' => 'required|date|before:today',
            'image' => 'required|mimes:jpg,png'
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        Employee::create([
            'employee_name' => $request->employee_name,
            'address' => $request->address,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'image' => $imageName,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'employee_name' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'phone' => 'required|numeric',
            'dob' => 'required|date|before:today',
            'image' => 'mimes:jpg,png'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $employee->image = $imageName;
        }

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
}
