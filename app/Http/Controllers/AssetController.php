<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $category_id = $request->category_id != 0 || $request->category_id != null ? $request->category_id : null;
        $company_id = $request->company_id != 0 || $request->company_id != null ? $request->company_id : null;
        $search = $request->search;
        $categories = Category::all();
        $companies = Company::where('status', 1)->with(['headquarters'])->get();

        if ($authUserRole != 'company') {
            if ($category_id && $company_id && $search && $search != '') {
                $assets = Asset::where(['status' => 'Activo', 'category_id' => $category_id, 'company_id' => $company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($category_id && $search && $search != '') {
                $assets = Asset::where(['status' => 'Activo', 'category_id' => $category_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($company_id && $search && $search != '') {
                $assets = Asset::where(['status' => 'Activo', 'company_id' => $company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($category_id) {
                $assets = Asset::where(['status' => 'Activo', 'category_id' => $category_id])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($company_id) {
                $assets = Asset::where(['status' => 'Activo', 'company_id' => $company_id])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($search && $search != '') {
                $assets = Asset::where(['status' => 'Activo'])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } else {
                $assets = Asset::where(['status' => 'Activo'])->orderBy('created_at', 'desc')->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            }
        } else {
            if ($category_id && $search && $search != '') {
                $assets = Asset::where(['status' => 'Activo', 'category_id' => $category_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($category_id) {
                $assets = Asset::where(['status' => 'Activo', 'company_id' => $authUser->company_id, 'category_id' => $category_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } elseif ($search && $search != '') {
                $assets = Asset::where(['status' => 'Activo', 'company_id' => $authUser->company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Activo']])->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            } else {
                $assets = Asset::where(['status' => 'Activo', 'company_id' => $authUser->company_id])->orderBy('created_at', 'desc')->paginate(10);
                return view('asset.assets', compact('assets', 'categories', 'companies'));
            }
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function inactive(Request $request)
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $category_id = $request->category_id != 0 || $request->category_id != null ? $request->category_id : null;
        $company_id = $request->company_id != 0 || $request->company_id != null ? $request->company_id : null;
        $search = $request->search;
        $categories = Category::all();
        $companies = Company::where('status', 1)->with(['headquarters'])->get();

        if ($authUserRole != 'company') {
            if ($category_id && $company_id && $search  && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo', 'category_id' => $category_id, 'company_id' => $company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($category_id && $search  && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo', 'category_id' => $category_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($company_id && $search && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo', 'company_id' => $company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($category_id) {
                $assets = Asset::where(['status' => 'Inactivo', 'category_id' => $category_id])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($company_id) {
                $assets = Asset::where(['status' => 'Inactivo', 'company_id' => $company_id])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($search  && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo'])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } else {
                $assets = Asset::where(['status' => 'Inactivo'])->orderBy('created_at', 'desc')->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            }
        } else {
            if ($category_id && $search  && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo', 'category_id' => $category_id, 'company_id' => $authUser->company_id,])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($category_id) {
                $assets = Asset::where(['status' => 'Inactivo', 'company_id' => $authUser->company_id, 'category_id' => $category_id])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } elseif ($search  && $search != '') {
                $assets = Asset::where(['status' => 'Inactivo', 'company_id' => $authUser->company_id])->where('code', 'like', '%' . $search . '%')->orWhere([['name', 'like', '%' . $search . '%'], ['status', 'Inactivo']])->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            } else {
                $assets = Asset::where(['status' => 'Inactivo', 'company_id' => $authUser->company_id])->orderBy('created_at', 'desc')->paginate(10);
                return view('asset.assets-inactive', compact('assets', 'categories', 'companies'));
            }
        }
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
            'code' => 'required|unique:assets|numeric',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'area' => 'required',
            'category_id' => 'required|numeric',
            'company_id' => 'required|numeric',
            'headquarter_id' => 'required|numeric',
            'brand' => 'required',
            'model' => 'required',
            'serie' => 'required',
            'state' => 'required',
            'status' => 'required',
            'performance' => 'required',
            'age' => 'required|numeric',
            'usefulLife' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        $asset = new Asset();

        $asset->code = $request->code;
        $asset->name = $request->name;
        $asset->quantity = $request->quantity;
        $asset->area = $request->area;
        $asset->category_id = $request->category_id;
        $asset->company_id = $request->company_id;
        $asset->headquarter_id = $request->headquarter_id;
        $asset->brand = $request->brand;
        $asset->model = $request->model;
        $asset->serie = $request->serie;
        $asset->state = $request->state;
        $asset->status = $request->status;
        $asset->performance = $request->performance;
        $asset->age = $request->age;
        $asset->usefulLife = $request->usefulLife;
        $asset->amount = $request->amount;

        $asset->save();

        return $asset;
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        // 

        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $asset = Asset::find($request->id)->load('files');

        $categories = Category::all();
        $companies = Company::where('status', 1)->with(['headquarters'])->get();
        if ($authUserRole != 'company') {
            return view('asset.asset-detail', compact('asset', 'companies', 'categories'));
        } else {
            if ($asset->company_id == $authUser->company_id) {
                return view('asset.asset-detail', compact('asset', 'companies', 'categories'));
            } else {
                abort(404);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset)
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
            'code' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'area' => 'required',
            'company_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'headquarter_id' => 'required|numeric',
            'brand' => 'required',
            'model' => 'required',
            'serie' => 'required',
            'state' => 'required',
            'status' => 'required',
            'performance' => 'required',
            'age' => 'required|numeric',
            'usefulLife' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        $asset = Asset::findOrFail($id);

        $asset->code = $request->code;
        $asset->name = $request->name;
        $asset->quantity = $request->quantity;
        $asset->area = $request->area;
        $asset->category_id = $request->category_id;
        $asset->company_id = $request->company_id;
        $asset->headquarter_id = $request->headquarter_id;
        $asset->brand = $request->brand;
        $asset->model = $request->model;
        $asset->serie = $request->serie;
        $asset->state = $request->state;
        $asset->status = $request->status;
        $asset->performance = $request->performance;
        $asset->age = $request->age;
        $asset->usefulLife = $request->usefulLife;
        $asset->amount = $request->amount;

        $asset->save();
        return $asset;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, String $id)
    {
        // Validate the request...
        $request->validate([
            'status' => 'required',
        ]);
        $asset = Asset::findOrFail($id);
        $asset->status = $request->status;

        $asset->save();
        return $asset;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        // delete asset
        $asset->delete();
        return redirect()->back();
    }
}
