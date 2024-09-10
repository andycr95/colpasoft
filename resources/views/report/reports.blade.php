@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Reportes'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @if (count($assets) > 0)
                <div class="d-flex flex-row align-items-end justify-content-end">
                    <form action="{{ route('reportes.export')}}" class="col-2" style="display: flex;margin-bottom: 10px;justify-content: end;padding: 0;">
                        @if (!Auth::user()->hasRole('company'))
                        <input type="hidden" name="company_id" value="{{ app('request')->input('company_id') }}">
                        @else
                        <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                        @endif
                        <input type="hidden" name="category_id" value="{{ app('request')->input('category_id') }}">
                            <button type="submit"
                                class="btn btn-success btn-sm mb-0 ml-3">Exportar</button>
                    </form>
                    <form action="{{ route('reportes.export.pdf')}}" class="col-2" style="display: flex;margin-bottom: 10px;justify-content: end;padding: 0;">
                        @if (!Auth::user()->hasRole('company'))
                        <input type="hidden" name="company_id" value="{{ app('request')->input('company_id') }}">
                        @else
                        <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                        @endif
                        <input type="hidden" name="category_id" value="{{ app('request')->input('category_id') }}">
                            <button type="submit"
                                class="btn btn-info btn-sm mb-0 ml-3">Exportar pdf</button>
                    </form>
                </div>
            @endif       
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-2">
                            <h6>Generar Reportes</h6>
                        </div>
                        <form action="{{ route('reportes.index')}}" class="col-lg-10">
                            <div class="d-flex flex-row justify-content-end gap-3">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label"
                                            >Buscar</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="search"
                                            placeholder="Buscar por area"
                                        />
                                    </div>
                                @if (!Auth::user()->hasRole('company'))
                                <div class="form-group" style="width: 250px">
                                    <label for="company_id">Empresa</label>
                                    <select
                                        class="form-control"
                                        id="company_id"
                                        value="{{ app('request')->input('company_id') }}"
                                        name="company_id"
                                        required
                                    >
                                        <option value="0">Todas</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}">
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @else
                                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                                @endif
                                <div class="form-group" style="width: 250px">
                                    <label for="category_id">Categoria</label>
                                    <select
                                        class="form-control"
                                        id="category_id"
                                        name="category_id"
                                        value="{{ app('request')->input('category_id') }}"
                                        required
                                    >
                                        <option value="0">Todas</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex flex-row align-items-center">
                                    <button type="submit"
                                            class="btn btn-info btn-sm mb-0 ml-3">Filtrar</button>   
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 300px">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Código</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Cantidad</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Empresa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Area</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Categoria</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Funcionamiento</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assets as $asset)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('activos.show', $asset) }}">
                                                    <h6 class="mb-0 text-sm">{{ $asset->code }}</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->name }}</p>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->quantity }}</p>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->company->name }}</p>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->area }}</p>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->category->name }}</p>
                                    </td>
                                    <td style="max-width: 200px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->state }}</p>
                                    </td>
                                    <td>
                                        @if($asset->status == 'Activo')
                                        <span class="badge badge-sm bg-gradient-success">Activo</span>
                                        @else
                                        <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection