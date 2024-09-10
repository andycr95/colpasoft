@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Empresas'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-2">
                            <h6>Empresas</h6>
                        </div>

                        <div class="col-10 d-flex flex-row justify-content-end">
                            <form action="{{ route('empresas.index')}}" class="col-10">
                                <div class="d-flex flex-row justify-content-end gap-3">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label"
                                            >Buscar</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="search"
                                            placeholder="Buscar por nit o nombre"
                                        />
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        <button type="submit"
                                                class="btn btn-info btn-sm mb-0 ml-3">Filtrar</button>   
                                                <button type="button"
                                class="btn btn-primary btn-sm mb-0 ml-3" id="company-modal-button">Agregar empresa</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 300px">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Nit</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Razón social</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dirección</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Teléfono</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Correo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                        Acciones</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('empresas.show', $company->id) }}">
                                                    <h6 class="mb-0 text-sm">{{ $company->nit }}</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $company->name }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $company->address }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $company->phone }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $company->email }}</p>
                                    </td>
                                    <td>
                                        @if($company->status)
                                        <span class="badge badge-sm bg-gradient-success">Activo</span>
                                        @else
                                        <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('empresas.show', $company->id) }}"
                                            class="text-secondary font-weight-bold text-xs">
                                            <span class="badge bg-gradient-info">Editar</span>
                                        </a>
                                        @if (Auth::user()->hasRole('admin'))
                                            <div class="d-none">
                                                <form action="{{ route('empresas.toogleActive', $company)}}" method="POST" id="empresasToogleActiveForm{{$company->id}}">
                                                    @csrf()
                                                    @method('PATCH')
                                                    <input type="hidden" name="id" value="{{ $company->id }}">
                                                </form>
                                            </div>
                                            @if($company->status)
                                                <a href="#"
                                                onClick="deleteCompany({{ $company->id }})"
                                                    class="text-secondary font-weight-bold text-xs">
                                                    <span class="badge bg-gradient-danger">Desactivar</span>
                                                </a>
                                            @else
                                                <a href="#"
                                                onClick="deleteCompany({{ $company->id }})"
                                                    class="text-secondary font-weight-bold text-xs">
                                                    <span class="badge bg-gradient-success">Activar</span>
                                                </a>
                                            @endif
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
        <div class="modal fade" role="dialog" id="company-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="app">
                    <create-company-component></create-company-component>
                </div>
            </div>
        </div>
    </div>
    <script is="script">
        const companyModalButton = document.getElementById('company-modal-button');
        const companyModalButtonClose = document.getElementById('company-modal-button-close');
        const companyModal = document.getElementById('company-modal');
        const companyModalButtonCancel = document.getElementById('company-modal-button-cancel');
        companyModalButton.addEventListener('click', () => {
            $(companyModal).modal('show');
        });
        companyModalButtonClose.addEventListener('click', () => {
            $(companyModal).modal('hide');
        });
        companyModalButtonCancel.addEventListener('click', () => {
            $(companyModal).modal('hide');
        });
        function deleteCompany(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Cambiarás el estado de la empresa!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('empresasToogleActiveForm'+id).submit();
                }
            });
        }
    </script>
    @include('layouts.footers.auth.footer')
</div>
@endsection