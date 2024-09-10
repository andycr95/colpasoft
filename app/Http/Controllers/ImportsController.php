<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Company;
use App\Models\Headquarter;

class ImportsController extends Controller
{
    //

    public function index()
    {
        return csrf_token();
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        $path = $request->file('file')->store('temp');
        $infoExcel = Excel::toCollection(new DataImport(), $path);

        Storage::delete($path);

        // Get the first row of the excel file
        $header = $infoExcel[0]->first();

        // Get the data from the excel file
        $data = $infoExcel[0];

        //remove the first row
        $data->shift();

        //remove rows with all empty values
        $data = $data->filter(function ($row) {
            return $row->filter(function ($cell) {
                return !is_null($cell);
            })->count() > 0;
        });

        return response()->json([
            'headers' => $header,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage with array data from request.
     */
    public function storeMany(Request $request)
    {
        $data = $request->data;
        $errors = [];
        foreach ($data as $row) {
            try {
                if (count($row) != 17) {
                    array_push($errors, 'Cantidad de columnas no es la necesaria');
                    continue;
                }
                $company_id = Company::where('nit', $row['nit_empresa'])->first();
                $headquarter_id = Headquarter::find($row['sede']);

                if (!$company_id) {
                    array_push($errors, 'Empresa no encontrada: ' . $row['nit_empresa'] . ' no se pudo importar el activo: ' . $row['codigo']);
                    continue;
                }

                if (!$headquarter_id) {
                    array_push($errors, 'Sede no encontrada: ' . $row['sede'] . ' no se pudo importar el activo: ' . $row['codigo']);
                    continue;
                }

                if ($headquarter_id->company_id != $company_id->id) {
                    array_push($errors, 'Sede no encontrada: ' . $row['sede'] . ' actualizar manualmente en el detaller del activo: ' . $row['codigo']);
                }
                $asset = Asset::where('code', $row['codigo'])->first();
                if ($asset) {
                    array_push($errors, 'Este activo ya existe, se modifica el existente, codigo: ' . $row['codigo']);
                    $asset->name = $row['nombre'];
                    $asset->quantity = $row['cantidad'];
                    $asset->area = $row['area'];
                    $asset->category_id = $row['categoria'];
                    if ($headquarter_id->company_id != $company_id->id) {
                        $asset->headquarter_id = null;
                    } else {
                        $asset->headquarter_id = $headquarter_id->id;
                    }
                    $asset->brand = $row['marca'];
                    $asset->model = $row['model'];
                    $asset->serie = $row['serie'];
                    $asset->state = $row['estado'];
                    $asset->status = $row['funcionamiento'];
                    $asset->performance = $row['rendimiento'];
                    $asset->age = $row['edad'];
                    $asset->usefulLife = $row['vida'];
                    $asset->amount = $row['valor'];
                    $asset->company_id = $company_id->id;
                    $asset->save();
                    continue;
                }


                $newAsset = new Asset();
                $newAsset->code = $row['codigo'];
                $newAsset->name = $row['nombre'];
                $newAsset->quantity = $row['cantidad'];
                $newAsset->area = $row['area'];
                $newAsset->category_id = $row['categoria'];
                $newAsset->headquarter_id = $headquarter_id->id;
                $newAsset->brand = $row['marca'];
                $newAsset->model = $row['model'];
                $newAsset->serie = $row['serie'];
                $newAsset->state = $row['estado'];
                $newAsset->status = $row['funcionamiento'];
                $newAsset->performance = $row['rendimiento'];
                $newAsset->age = $row['edad'];
                $newAsset->usefulLife = $row['vida'];
                $newAsset->amount = $row['valor'];
                $newAsset->company_id = $company_id->id;
                $newAsset->save();
            } catch (\Throwable $th) {
                array_push($errors, $th->getMessage());
            }
        }
        return response()->json([
            'message' => 'Data imported successfully',
            'errors' => $errors
        ]);
    }
}
