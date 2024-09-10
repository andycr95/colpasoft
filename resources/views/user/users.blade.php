@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Usuarios'])
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-2">
                            <h6>Usuarios</h6>
                        </div>

                        <div class="col-10 d-flex flex-row justify-content-end">
                            <form action="{{ route('usuarios.index')}}" class="col-12">
                                <div class="d-flex flex-row justify-content-end gap-3">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label"
                                            >Buscar</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            name="search"
                                            placeholder="Buscar por nombre"
                                        />
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        <button type="submit"
                                                class="btn btn-info btn-sm mb-0 ml-3">Filtrar</button>   
                                                <button type="button"
                                class="btn btn-primary btn-sm mb-0 ml-3" id="company-modal-button">Agregar usuario</button>
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
                                        ID</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nombre</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Correo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rol</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Estado</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                        Acciones</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <a href="{{ route('usuarios.show', $user->id) }}">
                                                    <h6 class="mb-0 text-sm">{{ $user->id }}</h6>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $user->name }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $user->email }}</p>
                                    </td>
                                    <td style="max-width: 250px">
                                        @if(!empty($user->getRoleNames()))
                                            @foreach($user->getRoleNames() as $v)
                                                @if ($v == 'admin')
                                                    <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">Administador</p>
                                                @elseif ($v == 'regularUser')
                                                    <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">Usuario</p>
                                                @else
                                                    <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">Cliente</p>
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>

                                    <td>
                                        @if($user->status)
                                            <span class="badge badge-sm bg-gradient-success">Activo</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('usuarios.show', $user->id) }}"
                                            class="text-secondary font-weight-bold text-xs">
                                            <span class="badge bg-gradient-info">Editar</span>
                                        </a>
                                        <div class="d-none">
                                            <form action="{{ route('usuarios.destroy', $user)}}" method="POST" id="deleteUserForm{{$user->id}}">
                                                @csrf()
                                                @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                            </form>
                                        </div>
                                        <a href="#"
                                        onClick="deleteUser({{ $user->id }})"
                                            class="text-secondary font-weight-bold text-xs">
                                            <span class="badge bg-gradient-danger">Eliminar</span>
                                        </a>
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
                    @isset($roles)
                        <create-user :roles="{{ $roles }}" :companies="{{ $companies }}"></create-user>
                    @else
                        <create-user :company="{{ $company }}"></create-user>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script is="script">
        const userModalButton = document.getElementById('company-modal-button');
        const userModal = document.getElementById('company-modal');
        userModalButton.addEventListener('click', () => {
            $(userModal).modal('show');
        });
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
                    document.getElementById('deleteUserForm'+id).submit();
                }
            });
        }
    </script>
    @include('layouts.footers.auth.footer')
</div>
@endsection