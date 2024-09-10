@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Categorias'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="col-lg">
                                <h6>Categorias</h6>
                            </div>
        <div class="col-sm d-flex flex-row-reverse">
            <button type="button"
            class="btn btn-primary btn-md mb-3" id="category-modal-button">Agregar categoria</button>
        </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2"style="min-height: 300px">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center justify-content-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            ID</th>
                                        <th
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nombre</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Descripción</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Activos</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Estado</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">
                                            Acciones</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $category->id }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $category->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="max-width: 250px">
                                                <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ $category->description }}</p>
                                            </td>
                                            <td style="max-width: 250px">
                                                <p class="text-sm font-weight-bold mb-0 w-100 text-truncate">{{ count($category->assets) }}</p>
                                            </td>
                                            <td>
                                                @if($category->status)
                                                    <span class="badge badge-sm bg-gradient-success">Activo</span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="#"
                                                onclick="editCategory({{ $category }})"
                                                    class="text-secondary font-weight-bold text-xs">
                                                    <span class="badge bg-gradient-info">Editar</span>
                                                </a>
                                                <div class="d-none">
                                                    <form action="{{ route('categorias.destroy', $category)}}" method="POST" id="deletecategoryForm{{$category->id}}">
                                                        @csrf()
                                                        @method('DELETE')
                                                        <input type="hidden" name="id" value="{{ $category->id }}">
                                                    </form>
                                                </div>
                                                <a href="#"
                                                onclick="deleteCategory({{ $category }})"
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
            <div class="modal fade" role="dialog" id="category-modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('categorias.update') }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Categorias</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <input type="text" class="form-control" name="description" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="category-modal-button-close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" role="dialog" id="category-edit">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('categorias.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Editar categoria</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="id">
                                <div class="form-group">
                                    <label for="name">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descripción</label>
                                    <input type="text" class="form-control" id="description" name="description" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button id="category-modal-button-close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-info">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const categoryModalButton = document.getElementById('category-modal-button');
            const categoryModalButtonClose = document.getElementById('category-modal-button-close');
            const categoryModal = document.getElementById('category-modal');
            categoryModalButton.addEventListener('click', () => {
                $(categoryModal).modal('show');
            });
            categoryModalButtonClose.addEventListener('click', () => {
                $(categoryModal).modal('hide');
            });
            function editCategory(category) {
                const categoryEdit = document.getElementById('category-edit');
                const id = document.getElementById('id');
                const name = document.getElementById('name');
                const description = document.getElementById('description');
                id.value = category.id;
                name.value = category.name;
                description.value = category.description;
                $(categoryEdit).modal('show');
            }

        function deleteCategory(category) {
            if(category.assets.length > 0) {
                Swal.fire({
                    title: 'No se puede eliminar',
                    text: 'La categoria tiene activos asociados',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
                return;
            }
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deletecategoryForm' + category.id);
                    form.submit();
                }
            });
        }
        </script>
        @include('layouts.footers.auth.footer')
    </div>
@endsection
