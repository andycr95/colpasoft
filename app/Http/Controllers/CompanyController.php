<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Company;
use App\Models\Headquarter;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        if ($search) {
            $companies = Company::where('nit', 'like', '%' . $search . '%')->orWhere('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $companies = Company::orderBy('created_at', 'desc')->paginate(10);
        }
        return view('company.companies', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'nit' => 'required|unique:companies|numeric',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ]);
        $company = new Company();

        $company->nit = $request->nit;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;

        $company->save();

        return redirect('empresas');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        // 
        $categories = Category::all();
        $companies = Company::all();
        $assets = Asset::where('company_id', $company->id)->paginate($perPage = 5, $columns = ['*'], $pageName = 'page_a');
        $users = User::where('company_id', $company->id)->paginate($perPage = 15, $columns = ['*'], $pageName = 'page_u');
        $headquarters = Headquarter::where('company_id', $company->id)->paginate($perPage = 15, $columns = ['*'], $pageName = 'page_h');
        return view('company.company-detail', compact('company', 'categories', 'companies', 'assets', 'users', 'headquarters'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate the request...
        $request->validate([
            'nit' => 'required|numeric',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
        ]);
        $company = Company::findOrFail($id);

        $company->nit = $request->nit;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->phone = $request->phone;
        $company->email = $request->email;

        $company->save();
        return $company;
    }

    public function updateActive(Company $company)
    {
        $company->status = !$company->status;
        $company->save();

        $headquarters = Headquarter::where('company_id', $company->id)->get();
        foreach ($headquarters as $headquarter) {
            $headquarter->status = $company->status;
            $headquarter->save();
        }

        return redirect('empresas');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //Delete company if it has no assets or users
        $company = Company::findOrFail($id);

        if ($company->assets->count() == 0 && $company->users->count() == 0) {
            $company->status = !$company->status;
            $company->save();

            $headquarters = Headquarter::where('company_id', $company->id)->get();
            foreach ($headquarters as $headquarter) {
                $headquarter->status = $company->status;
                $headquarter->save();
            }
            return redirect('empresas');
        } else {
            return redirect('empresas')->with('error', 'No se puede eliminar la empresa, tiene activos o usuarios asociados');
        }
    }
}
