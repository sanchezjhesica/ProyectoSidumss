@extends('layouts.operador')

@section('content')
<div class="operador-stellar">
    <!-- 1. ENCABEZADO DEL PANEL -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 mb-md-5 border-bottom pb-4 gap-3">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-tasks me-2"></i> Panel de Trabajo - Operador</h2>
            <p class="text-muted mb-0">Gestión de lecturas de medidores y generación de cobros.</p>
        </div>
    </div>

    {{-- 2. SECCIÓN DE ALERTAS --}}
    @if(session('success'))
        <div class="alert alert-stellar alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-stellar-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Atención:</strong>
            <ul class="mb-0 small mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        {{-- 3. TARJETA DE REGISTRO --}}
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-4 text-center">
                    <h4 class="mb-0 text-stellar-blue fw-bold"><i class="fas fa-faucet me-2"></i> Registrar Consumo de Agua</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('operador.lecturas.store') }}" method="POST">
                        @csrf
                        
                        <!-- Selección de Vivienda -->
                        <div class="mb-4">
                            <label class="form-label-stellar">Seleccionar Vivienda / Casa</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-home text-muted"></i></span>
                                <select name="id_vivienda" id="select-vivienda" class="form-select form-stellar border-start-0 py-2" required>
                                    <option value="" selected disabled>-- Elija una casa para comenzar --</option>
                                    @foreach($viviendas as $v)
                                        <option value="{{ $v->id_vivienda }}" data-anterior="{{ $v->ultima_lectura }}">
                                            Casa Nro. {{ $v->nro_casa }} (Medidor: {{ $v->nro_medidor }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- SELECCIÓN DE PERIODO -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label-stellar">Mes de Lectura</label>
                                <select name="mes" class="form-select form-stellar py-2" required>
                                    @php $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; @endphp
                                    @foreach($meses as $index => $mes)
                                        <option value="{{ $index + 1 }}" {{ (now()->month == $index + 1) ? 'selected' : '' }}>{{ $mes }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-stellar">Año correspondiente</label>
                                <input type="number" name="anio" class="form-control form-stellar py-2" value="{{ now()->year }}" required>
                            </div>
                        </div>

                        {{-- Cuadro informativo de lectura anterior --}}
                        <div id="info-anterior" class="d-none mb-4 p-4 bg-blue-soft rounded-3 border-start border-stellar-blue border-4 shadow-sm animate__animated animate__fadeIn">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-history text-stellar-blue me-2"></i> 
                                    <span class="text-muted fw-bold small text-uppercase">Lectura anterior:</span>
                                </div>
                                <h3 class="mb-0 fw-bold text-stellar-blue"><span id="valor-anterior">0</span> <small class="fw-normal">m³</small></h3>
                            </div>
                        </div>

                        <!-- Lectura Actual -->
                        <div class="mb-5">
                            <label class="form-label-stellar">Lectura Actual del Medidor (m³)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-tint text-info"></i></span>
                                <input type="number" name="lectura_actual" step="0.01" 
                                    class="form-control form-stellar border-start-0 fs-4 fw-bold text-stellar-blue" 
                                    placeholder="0.00" required>
                                <span class="input-group-text bg-light fw-bold text-muted">m³</span>
                            </div>
                        </div>

                        <!-- Botón de Acción Principal -->
                        <div class="pt-4 border-top">
                            <div class="alert bg-light border-0 rounded-3 small text-muted mb-4">
                                <i class="fas fa-info-circle me-2"></i> Al guardar, el sistema calculará automáticamente el consumo y generará los avisos de cobro del periodo.
                            </div>
                            <button type="submit" class="btn btn-stellar-blue btn-lg w-100 py-3 shadow-sm">
                                <i class="fas fa-save me-2"></i> Guardar Lectura y Procesar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* VARIABLES STELLAR BLUE/GREEN */
    :root {
        --stellar-blue: #0e5cad;
        --stellar-grad: linear-gradient(45deg, #79f1a4 15%, #0e5cad 85%);
        --stellar-button: linear-gradient(45deg, #22349e 0%, #8183e6 100%);
        --stellar-input-focus: rgba(14, 92, 173, 0.1);
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    .border-stellar-blue { border-color: var(--stellar-blue) !important; }

    .form-label-stellar {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #777;
        margin-bottom: 8px;
        display: block;
    }

    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        background-color: #fdfdfd;
        transition: all 0.3s ease;
    }
    .form-stellar:focus {
        border-color: var(--stellar-blue);
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem var(--stellar-input-focus);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px !important;
        border-color: #e0e0e0;
    }

    /* Botón Estilo Stellar */
    .btn-stellar-blue {
        background: var(--stellar-button);
        color: white !important;
        border-radius: 50px;
        font-weight: 700;
        border: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }
    .btn-stellar-blue:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(34, 52, 158, 0.3) !important;
    }

    /* Alertas */
    .alert-stellar { background: #fff; border-left: 5px solid var(--stellar-blue); color: var(--stellar-blue); border-radius: 8px; }
    .alert-stellar-danger { background: #fff; border-left: 5px solid #e74c3c; color: #e74c3c; border-radius: 8px; }
    
    .rounded-4 { border-radius: 1.25rem !important; }
</style>

<script>
    document.getElementById('select-vivienda').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let anterior = selectedOption.getAttribute('data-anterior');
        
        let infoBox = document.getElementById('info-anterior');
        let valorSpan = document.getElementById('valor-anterior');

        if (anterior !== null && anterior !== "") {
            infoBox.classList.remove('d-none');
            valorSpan.innerText = anterior;
        } else {
            infoBox.classList.add('d-none');
        }
    });
</script>
@endsection