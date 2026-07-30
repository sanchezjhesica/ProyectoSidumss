@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Configuración de Tarifas y Cuotas</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Ajustar Precios del Sistema
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tarifas.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Precio por m³ de Agua (Bs)</label>
                        <input type="number" step="0.01" name="precio_por_m3_agua" class="form-control" value="{{ $tarifa->precio_por_m3_agua ?? '' }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cuota Fija Mantenimiento (Bs)</label>
                        <input type="number" step="0.01" name="monto_fijo_mantenimiento" class="form-control" value="{{ $tarifa->monto_fijo_mantenimiento ?? '' }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Monto Alcantarillado (Bs)</label>
                        <input type="number" step="0.01" name="monto_alcantarillado" class="form-control" value="{{ $tarifa->monto_alcantarillado ?? '' }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Porcentaje de Mora (%)</label>
                        <input type="number" step="0.01" name="porcentaje_mora" class="form-control" value="{{ $tarifa->porcentaje_mora ?? '' }}" required>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="mt-4 p-3 bg-light border rounded">
        <small class="text-muted">
            <strong>Nota:</strong> Los cambios realizados aquí se aplicarán a todos los nuevos avisos de cobro que se generen a partir de ahora.
        </small>
    </div>
</div>
@endsection