@extends('layouts.propietario')

@section('content')
<div class="container">
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Bienvenido, Propietario</h1>
            <p class="col-md-8 fs-4">Desde aquí puede gestionar sus avisos de cobranza, revisar sus consumos de agua y realizar reservas para las áreas recreativas de la urbanización.</p>
            <hr class="my-4">
            <div class="d-flex gap-3">
                <a href="{{ route('propietario.avisos') }}" class="btn btn-primary btn-lg">Ver Mis Avisos</a>
                <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline-dark btn-lg">Reservar Wally / Salón</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Mis Propiedades</h5>
                    <p class="card-text">Usted tiene registradas las siguientes viviendas bajo su nombre.</p>
                    <ul class="list-group">
                        @foreach($viviendas as $v)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Casa {{ $v->nro_casa }}
                                <span class="badge bg-primary rounded-pill">Medidor: {{ $v->nro_medidor }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection