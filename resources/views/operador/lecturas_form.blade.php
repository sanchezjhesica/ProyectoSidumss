@extends('layouts.operador')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-primary"><i class="fas fa-tasks"></i> Panel de Trabajo - Operador</h2>

    {{-- SECCIÓN DE ALERTAS (RESPUESTA DE LA TRANSACCIÓN) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <strong>Atención:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mt-4">
        {{-- COLUMNA 1: REGISTRO DE CONSUMO --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow border-primary h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-faucet"></i> Registrar Consumo de Agua</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Al guardar, se generará la deuda automáticamente.</p>
                    
                    <form action="{{ route('operador.lecturas.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Seleccionar Vivienda</label>
                            <select name="id_vivienda" id="select-vivienda" class="form-select form-select-lg" required>
                                <option value="" selected disabled>-- Seleccione una casa --</option>
                                @foreach($viviendas as $v)
                                    <option value="{{ $v->id_vivienda }}" data-anterior="{{ $v->ultima_lectura }}">
                                        Casa {{ $v->nro_casa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cuadro informativo de lectura anterior --}}
                        <div id="info-anterior" class="alert alert-secondary d-none border-start border-4 border-info">
                            <i class="fas fa-history"></i> Lectura anterior: <strong id="valor-anterior">0</strong> m³
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Lectura Actual (m³)</label>
                            <div class="input-group">
                                <input type="number" name="lectura_actual" step="0.001" 
                                    class="form-control form-control-lg border-primary" 
                                    placeholder="Ingrese números negros y rojos" required>
                                <span class="input-group-text bg-primary text-white">m³</span>
                            </div>
                            <div class="form-text">Ejemplo: Para 00001,3 ponga 1.3</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-save"></i> Guardar y Generar Cobro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

{{-- SCRIPT PARA LA LECTURA ANTERIOR DINÁMICA --}}
<script>
    document.getElementById('select-vivienda').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let anterior = selectedOption.getAttribute('data-anterior');
        
        let infoBox = document.getElementById('info-anterior');
        let valorSpan = document.getElementById('valor-anterior');

        if (anterior !== null) {
            infoBox.classList.remove('d-none');
            valorSpan.innerText = anterior;
        } else {
            infoBox.classList.add('d-none');
        }
    });
</script>

{{-- Iconos de FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection