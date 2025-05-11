<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class CompanyController extends Controller
{


    
    public function cadastro_externo()
    {
        return view('companies.create_externo');
    }
    public function index()
    {
        return view('companies.index');
    }

    public function list()
    {
        return Company::orderBy('name')->get(['id', 'name']);
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
        $validatedData = $request->validate([
            // Dados da Empresa
            'name' => 'required|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'cnpj' => 'required|string|unique:companies,cnpj|size:18', // 00.000.000/0000-00
            'state_registration' => 'nullable|string|max:20',
            
            // Contatos
            'phone' => 'required|string|size:14', // (00) 0000-0000
            'whatsapp' => 'nullable|string|size:15', // (00) 00000-0000
            'email' => 'required|email|unique:companies,email|max:255',
            
            // Endereço
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|size:9', // 00000-000
            
            // Segurança
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
            
            // Informações Adicionais
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
        ]);
    
        // Remove a confirmação de senha antes de criar
        unset($validatedData['password_confirmation']);
    
        // Remove máscaras dos campos antes de salvar
        $validatedData['cnpj'] = preg_replace('/[^0-9]/', '', $validatedData['cnpj']);
        $validatedData['phone'] = preg_replace('/[^0-9]/', '', $validatedData['phone']);
        $validatedData['whatsapp'] = $validatedData['whatsapp'] ? preg_replace('/[^0-9]/', '', $validatedData['whatsapp']) : null;
        $validatedData['zip_code'] = preg_replace('/[^0-9]/', '', $validatedData['zip_code']);
    
        // Cria a empresa com os dados validados
        Company::create($validatedData);
    
        return redirect()->route('companies.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
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
