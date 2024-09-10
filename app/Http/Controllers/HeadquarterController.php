<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Company;
use App\Models\Headquarter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadquarterController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $companies = Company::where('status', 1)->get();
        if ($authUserRole != 'company') {
            $headquarters = Headquarter::paginate(10);
        } else {
            $headquarters = Headquarter::where(['company_id' => $authUser->company_id])->paginate(10);
        }

        return view('headquarter.headquarters', compact('headquarters', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'company_id' => 'required|numeric',
        ]);
        $headquarter = new Headquarter();

        $headquarter->name = $request->name;
        $headquarter->address = $request->address;
        $headquarter->company_id = $request->company_id;

        $headquarter->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Headquarter $headquarter)
    {
        $assets = Asset::where('headquarter_id', $headquarter->id)->paginate($perPage = 5, $columns = ['*'], $pageName = 'page_a');
        $companies = Company::all();
        return view('headquarter.headquarter-detail', compact('headquarter', 'assets', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate the request...
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'company_id' => 'required|numeric',
        ]);
        $headquarter = Headquarter::findOrFail($id);

        $headquarter->name = $request->name;
        $headquarter->address = $request->address;
        $headquarter->company_id = $request->company_id;

        $headquarter->save();
        return $headquarter;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //Delete company if it has no assets or users
        $headquarter = Headquarter::findOrFail($id);

        if ($headquarter->assets->count() == 0) {
            $headquarter->status = !$headquarter->status;
            return redirect()->back();
        } else {
            return redirect()->back()->with('error', 'No se puede eliminar la sede, tiene activos');
        }
    }
}
