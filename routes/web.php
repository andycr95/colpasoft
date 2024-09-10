<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HeadquarterController;
use App\Http\Controllers\ImportsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categoría views
    Route::get('/categorias/{id}', function (string $id) {
        return new CategoryController(Category::findOrFail($id));
    });
    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categorias.store');
    Route::patch('/categorias', [CategoryController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{category}', [CategoryController::class, 'destroy'])->name('categorias.destroy');

    // Empresa views
    Route::get('/empresas', [CompanyController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/{company}', [CompanyController::class, 'show'])->name('empresas.show');

    // Empresa api
    Route::post('api/empresas', [CompanyController::class, 'store'])->name('empresas.store');
    Route::patch('api/empresas/{id}', [CompanyController::class, 'update'])->name('empresas.update');
    Route::patch('/empresas/update-active/{company}', [CompanyController::class, 'updateActive'])->name('empresas.toogleActive');


    // sede views
    Route::get('/sedes', [HeadquarterController::class, 'index'])->name('sedes.index');
    Route::get('/sedes/{headquarter}', [HeadquarterController::class, 'show'])->name('sedes.show');

    // sede api
    Route::post('api/sedes', [HeadquarterController::class, 'store'])->name('sedes.store');
    Route::patch('api/sedes/{id}', [HeadquarterController::class, 'update'])->name('sedes.update');
    Route::delete('/sedes/{id}', [HeadquarterController::class, 'destroy'])->name('sedes.destroy');

    //Activos views
    Route::get('/activos', [AssetController::class, 'index'])->name('activos.index');
    Route::get('/papelera', [AssetController::class, 'inactive'])->name('activos.inactive');
    Route::get('/activos/{id}', [AssetController::class, 'show'])->name('activos.show');
    //Activos api
    Route::post('api/activos', [AssetController::class, 'store'])->name('activos.store');
    Route::patch('api/activos/{id}', [AssetController::class, 'update'])->name('activos.update');
    Route::patch('api/activos/estado/{id}', [AssetController::class, 'updateStatus'])->name('activos.updateStatus');
    Route::delete('activos/{asset}', [AssetController::class, 'destroy'])->name('activos.destroy');

    //Usuarios views
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
    //Usuarios api
    Route::post('api/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::patch('api/usuarios/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::patch('usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');

    //Usuarios views
    Route::get('/reportes', [ReportsController::class, 'index'])->name('reportes.index');
    // reportes api
    Route::get('/reportes/exportar', [ReportsController::class, 'export'])->name('reportes.export');
    Route::get('/reportes/exportarPdf', [ReportsController::class, 'exportPdf'])->name('reportes.export.pdf');
    // Route::patch('api/reportes/{id}', [UserController::class, 'update'])->name('reportes.update');
    // Route::patch('api/reportes/{id}', [UserController::class, 'destroy'])->name('reportes.destroy');


    //Files
    Route::post('/api/subir-archivo', [FileController::class, 'store']);
    // Download
    Route::get('/api/descargar-archivo/{file}', [FileController::class, 'download'])->name('file.download');
});


//Import excel file
Route::get('api/import', [ImportsController::class, 'index']);
Route::post('api/import-excel', [ImportsController::class, 'importExcel'])->name('import.excel');
Route::post('api/import', [ImportsController::class, 'storeMany'])->name('import.store');


require __DIR__ . '/auth.php';
