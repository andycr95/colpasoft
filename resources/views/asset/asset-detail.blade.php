@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Perfil de activo'])
<div class="card shadow-lg mx-4">
    <div class="card-body p-3">
        <div class="row gx-4">
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $asset->name }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ $asset->code }}
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li class="nav-item">
                            @if($asset->status == 'Activo')
                            <h2 class="ms-2 text-success">{{ $asset->status }}</h2>
                            @else
                            <h2 class="ms-2 text-danger">{{ $asset->status }}</h2>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-8">
            <div class="card" id="app">
                <edit-asset :auth="{{Auth::user()->getRoleNames()}}" :asset="{{ json_encode($asset) }}" :categories="{{ json_encode($categories)}}" :companies="{{ json_encode($companies)}}"></edit-asset>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="h3 mb-0">Archivos</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach ($asset->files as $file)
                        <a href="{{ route('file.download', $file->id) }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="ms-2">
                                <div class="d-flex justify-content-between w-100">
                                    <h6 class="mb-1">{{ $file->name }}</h6>
                                    <small>{{ $file->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection