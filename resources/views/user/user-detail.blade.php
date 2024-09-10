@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Perfil de usuario'])
<div class="card shadow-lg mx-4">
    <div class="card-body p-3">
        <div class="row gx-4">
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ $user->name }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ $user->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid py-4" id="app">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <edit-user :companies="{{$companies}}" :roles="{{$roles}}" :user="{{$user}}" :auth="{{ $authUser }}"></edit-user>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
</div>
@endsection