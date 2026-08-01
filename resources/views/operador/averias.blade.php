@extends('layouts.operador')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-danger"><i class="fas fa-tools"></i> Gestión de Averías Técnicas</h2>

    {{-- Alertas de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- COLUMNA 1: FORMULARIO PARA REPORTAR (NUEVO) -->
        <div class="col-md-5">
            <div class="card shadow border-danger mb-4">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Crear Nuevo Reporte</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('operador.averias.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Vivienda con Problema</label>
                            <select name="id_vivienda" class="form-select form-select-lg border-danger" required>
                                <option value="" selected disabled>-- Seleccione Casa --</option>
                                @foreach($viviendas as $v)
                                    <option value="{{ $v->id_vivienda }}">Casa {{ $v->nro_casa }} (Medidor: {{ $v->nro_medidor }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción del Problema</label>
                            <textarea name="descripcion" class="form-control border-danger" rows="5" 
                                placeholder="Ej: El medidor gotea, el vidrio está roto, no marca el consumo..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg shadow">
                                <i class="fas fa-paper-plane"></i> Enviar Reporte al Administrador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- COLUMNA 2: HISTORIAL DE REPORTES -->
        <div class="col-md-7">
            <div class="card shadow border-dark h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Historial de Reportes</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Casa</th>
                                    <th>Problema</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($averias as $av)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($av->fecha_reporte)->format('d/m/Y') }}</td>
                                    <td><strong>{{ $av->nro_casa }}</strong></td>
                                    <td class="small">{{ Str::limit($av->descripcion_problema, 40) }}</td>
                                    <td>
                                        @if($av->estado_reparacion == 'Pendiente')
                                            <span class="badge bg-danger">Pendiente</span>
                                        @elseif($av->estado_reparacion == 'En Proceso')
                                            <span class="badge bg-warning text-dark">En Proceso</span>
                                        @else
                                            <span class="badge bg-success">Reparado</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-double fa-2x mb-2"></i><br>
                                        No hay averías registradas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Estilos extra para mejorar la UI --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection