@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Perfil de empresa'])
<div class="card shadow-lg mx-4">
    <div class="card-body p-3">
        <div class="row gx-4">
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $headquarter->name }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ $headquarter->id }}
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li class="nav-item">
                            <h2 class="ms-2">Activos: {{count($headquarter->assets)}}</h2>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4">
    <div class="d-flex w-100">
        <div class="row">
            <div class="col-12">
                <div class="card" id="app">
                    <edit-headquarter :companies="{{ $companies }}" :headquarter="{{ json_encode($headquarter) }}" :auth="{{Auth::user()->getRoleNames()}}"></edit-headquarter>
                </div>
            </div>
            <div class="col-12 py-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg">
                                <h6>Activos</h6>
                            </div>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Categoria</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Funcionamiento</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Acciones</th>
                                        <th></th>
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
                                            <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->category->name }}</p>
                                        </td>
                                        <td style="max-width: 200px">
                                            <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $asset->state }}</p>
                                        </td>
                                        <td>
                                            @if($asset->status)
                                            <span class="badge badge-sm bg-gradient-success">Activo</span>
                                            @else
                                            <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <a href="{{ route('activos.show', $asset) }}"
                                                class="text-secondary font-weight-bold text-xs">
                                                <span class="badge bg-gradient-info">Editar</span>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="12">
                                            <div class="d-flex justify-content-center">
                                                {{ $assets->links('vendor.pagination.bootstrap-5') }}
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script is="script">
        function deleteUser(id, token) {
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
                    document.getElementById('deleteUserForm' + id).submit();
                }
            });
        }
    </script>
    @include('layouts.footers.auth.footer')
</div>
@endsection