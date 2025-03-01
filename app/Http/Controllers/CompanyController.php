<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class CompanyController extends Controller
{
    public function index()
    {
        return view('companies.index');
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('companies.show', compact('company'));
    }

    public function getData()
    {
        $companies = Company::query();

        return DataTables::of($companies)
            ->addColumn('actions', function ($company) {
                return '
                    <a href="'.route('shipments.index', ['company_id' => $company->id]).'" class="btn btn-primary btn-sm">🚚 Ver Fretes</a>
                    <a href="'.route('shipments.index', ['company_id' => $company->id]).'" class="btn btn-secondary btn-sm">📦 Ver Cargas</a>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|unique:companies,cnpj|size:18', // 18 caracteres com máscara
            'phone' => 'nullable|string|size:15', // 15 caracteres com máscara
            'email' => 'required|email|unique:companies,email',
            'address' => 'required|string|max:255',
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cnpj' => 'required|string|size:18|unique:companies,cnpj,' . $company->id,
            'phone' => 'nullable|string|size:15',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'address' => 'required|string|max:255',
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
    }
}
