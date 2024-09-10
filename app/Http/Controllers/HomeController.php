<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //

    public function index()
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        if ($authUserRole != 'company') {
            $assetsCount = Asset::where('active', 1)->count();
            $usersCount = User::all()->count();
            $companiesCount = Company::where('status', 1)->count();

            // ultimos 10 activos
            $assets = Asset::where('active', 1)->orderBy('id', 'desc')->take(10)->get();

            return view('pages.dashboard', compact('assetsCount', 'usersCount', 'companiesCount', 'assets'));
        } else {
            $assetsCount = Asset::where('active', 1)->where('company_id', $authUser->company_id)->count();
            $usersCount = User::where('company_id', $authUser->company_id)->count();
            $assets = Asset::where(['active' => 1, 'company_id' => $authUser->company_id])->orderBy('id', 'desc')->take(10)->get();
            return view('pages.dashboard', compact('assetsCount', 'usersCount', 'assets'));
        }
    }
}
