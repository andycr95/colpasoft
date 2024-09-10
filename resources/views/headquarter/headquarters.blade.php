@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Sedes'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg">
                            <h6>Sedes</h6>
                        </div>
                        @if (!Auth::user()->hasRole('company'))
                        <div class="col-sm d-flex flex-row-reverse">
                            <button type="button"
                                class="btn btn-primary btn-md mb-3" id="company-modal-button">Agregar sede</button>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 300px">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Dirección</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Empresa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Activos</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($headquarters as $headquarter)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('sedes.show', $headquarter->id) }}">
                                                    <h6 class="mb-0 text-sm">{{ $headquarter->id }}</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $headquarter->name }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $headquarter->address }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $headquarter->company->name }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ count($headquarter->assets)}}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('sedes.show', $headquarter->id) }}"
                                            class="text-secondary font-weight-bold text-xs">
                                            <span class="badge bg-gradient-info">Editar</span>
                                        </a>
                                        @if (!Auth::user()->hasRole(['company', 'regularUser']))
                                        <div class="d-none">
                                            <form action="{{ route('sedes.destroy', $headquarter)}}" method="POST" id="sedesDeleteForm{{$headquarter->id}}">
                                                @csrf()
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $headquarter->id }}">
                                            </form>
                                        </div>
                                            <a href="#"
                                            onClick="deleteSede({{ $headquarter }})"
                                                class="text-secondary font-weight-bold text-xs">
                                                <span class="badge bg-gradient-danger">Eliminar</span>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="12">
                                            <div class="d-flex justify-content-center">
                                                {{ $headquarters->links('vendor.pagination.bootstrap-5') }}
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" role="dialog" id="company-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="app">
                    <create-headquarter :companies="{{ $companies }}"></create-headquarter>
                </div>
            </div>
        </div>
    </div>
    <script is="script">
        const companyModalButton = document.getElementById('company-modal-button');
        const companyModal = document.getElementById('company-modal');
        companyModalButton.addEventListener('click', () => {
            $(companyModal).modal('show');
        });
        function deleteSede(headquarter) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    if(headquarter.assets.length > 0){
                        Swal.fire({
                            title: '¡No es posible!',
                            text: "¡Esta sede tiene activos asociados!",
                            icon: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok!'
                        })
                    } else{
                        document.getElementById('sedesDeleteForm'+headquarter.id).submit();
                    }
                }
            });
        }
    </script>
    @include('layouts.footers.auth.footer')
</div>
@endsection