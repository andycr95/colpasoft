<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    //

    public function index(Request $request)
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $company_id = $request->company_id != 0 || $request->company_id != null ? $request->company_id : null;
        $search = $request->search;
        $categories = Category::all();
        $companies = Company::where('status', 1)->with(['headquarters'])->get();

        if ($authUserRole != 'company') {
            if ($request->category_id && $company_id && $search && $search != '' && $request->category_id != 0) {
                $assets = Asset::where(['active' => 1, 'category_id' => $request->category_id, 'company_id' => $company_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($request->category_id && $search && $search != '' && $request->category_id != 0) {
                $assets = Asset::where(['active' => 1, 'category_id' => $request->category_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($company_id && $search && $search != '') {
                $assets = Asset::where(['active' => 1, 'company_id' => $company_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($request->category_id && $request->category_id != 0) {
                $assets = Asset::where(['active' => 1, 'category_id' => $request->category_id])->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($company_id) {
                $assets = Asset::where(['active' => 1, 'company_id' => $company_id])->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($search) {
                $assets = Asset::where(['active' => 1])->where('code', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($request->category_id == 0) {
                $assets = Asset::where(['active' => 1, 'category_id' => $request->category_id])->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } else {
                $assets = [];
                return view('report.reports', compact('assets', 'companies', 'categories'));
            }
        } else {
            if ($request->category_id && $search && $search != '' && $request->category_id != 0) {
                $assets = Asset::where(['active' => 1, 'category_id' => $request->category_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($request->category_id) {
                $assets = Asset::where(['active' => 1, 'company_id' => $authUser->company_id, 'category_id' => $request->category_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($search && $search != '') {
                $assets = Asset::where(['active' => 1, 'company_id' => $authUser->company_id])->where('area', 'like', '%' . $search . '%')->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            } elseif ($request->category_id == 0) {
                $assets = Asset::where(['active' => 1, 'company_id' => $authUser->company_id])->paginate(10);
                return view('report.reports', compact('assets', 'companies', 'categories'));
            }else {
                $assets = [];
                return view('report.reports', compact('assets', 'companies', 'categories'));
            }
        }
    }


    public function export(Request $request)
    {
        $newDate = new DateTime('now');
        $filename = 'activos-' . $newDate->format('Y-m-d') . '.xlsx';
        
        return (new DataExport)
            ->forCaterogry($request->category_id)
            ->forCompany($request->company_id)
            ->forStatus($request->status)
            ->download($filename);
    }

    public function exportPdf(Request $request)
    {
        $date = new DateTime('now');
        $name = 'activos-' . $date->format('Y-m-d');
        $category_id = $request->category_id;
        $company_id = $request->company_id;
        $status = $request->status;

        if ($category_id && $company_id && $status) {
            $assets = Asset::where(['active' => 1, 'category_id' => $category_id, 'company_id' => $company_id, 'status' => $status])->get();
        } elseif ($category_id && $company_id) {
            $assets = Asset::where(['active' => 1, 'category_id' => $category_id, 'company_id' => $company_id])->get();
        } elseif ($category_id && $status) {
            $assets = Asset::where(['active' => 1, 'category_id' => $category_id, 'status' => $status])->get();
        } elseif ($company_id && $status) {
            $assets = Asset::where(['active' => 1, 'company_id' => $company_id, 'status' => $status])->get();
        } elseif ($category_id) {
            $assets = Asset::where(['active' => 1, 'category_id' => $category_id])->get();
        } elseif ($company_id) {
            $assets = Asset::where(['active' => 1, 'company_id' => $company_id])->get();
        } else {
            $assets = Asset::where(['active' => 1, 'status' => $status])->get();
        }

        $pdf = Pdf::loadView('pdf-view.pdf-report', compact('assets', 'name'))->setPaper('a2', 'landscape');

        return $pdf->download('report.pdf');
    }
}
