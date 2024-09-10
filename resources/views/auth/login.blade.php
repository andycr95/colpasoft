@extends('layouts.app')

@section('content')
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Iniciar sesión</h4>
                                    <p class="mb-0">Ingresa tu email y contraseña para iniciar sesión</p>
                                </div>
                                <div class="card-body">
                                        <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
                                        <div class="mb-3">
                                            <input type="email" class="form-control form-control-lg" id="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                                aria-label="Email">
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <div class="mb-3">
                                            <input type="password" class="form-control form-control-lg"
                            name="password"
                            required autocomplete="current-password" 
                                                aria-label="Password">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>
                                        <div class="form-check form-switch">
                                            <input name="remember" class="form-check-input" type="checkbox" id="remember_me">
                                            <label class="form-check-label" for="rememberMe">Recordarme</label>
                                        </div>
                                        @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Ingresa</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div style="background-color: #DB721B" class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden">
                                <div class="position-relative z-index-2 mb-3">
                                    <img class="max-width-500 w-100 position-relative z-index-2"
                                        src="/img/colpa-logo.jpeg" alt="illustration">
                                </div>
                                <h2 class="mt-5 text-white font-weight-bolder position-relative">ColpaSoft - Sistema de Administración de Activos</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
