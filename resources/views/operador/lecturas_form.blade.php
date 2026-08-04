@extends('layouts.operador')

@section('content')
<div class="operador-stellar">
    <!-- 1. ENCABEZADO DEL PANEL -->
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-4">
        <div>
            <h2 class="text-purple mb-0"><i class="fas fa-tasks me-2"></i> Panel de Trabajo - Operador</h2>
            <p class="text-muted mb-0">Gestión de lecturas de medidores y generación automática de cobros.</p>
        </div>
    </div>

    {{-- 2. SECCIÓN DE ALERTAS ESTILIZADAS --}}
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
        {{-- 3. TARJETA DE REGISTRO (Estilo Stellar Card) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-4 text-center">
                    <h4 class="mb-0 text-purple fw-bold"><i class="fas fa-faucet me-2"></i> Registrar Consumo de Agua</h4>
                </div>
                <div class="card-body p-5">
                    <p class="text-center text-muted small mb-5">Seleccione la vivienda y cargue la lectura actual del medidor físico.</p>
                    
                    <form action="{{ route('operador.lecturas.store') }}" method="POST">
                        @csrf
                        
                        <!-- Selección de Vivienda -->
                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark text-uppercase small tracking-wider">Seleccionar Vivienda</label>
                            <select name="id_vivienda" id="select-vivienda" class="form-select form-stellar py-3" required>
                                <option value="" selected disabled>-- Elija una casa para comenzar --</option>
                                @foreach($viviendas as $v)
                                    <option value="{{ $v->id_vivienda }}" data-anterior="{{ $v->ultima_lectura }}">
                                        Casa Nro. {{ $v->nro_casa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cuadro informativo de lectura anterior (Stellar Soft Style) --}}
                        <div id="info-anterior" class="d-none mb-5 p-4 bg-purple-soft rounded-3 border-start border-purple border-4 shadow-sm">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-history text-purple me-2"></i> 
                                    <span class="text-muted">Lectura anterior registrada:</span>
                                </div>
                                <h3 class="mb-0 fw-bold text-purple"><span id="valor-anterior">0</span> <small class="fw-normal">m³</small></h3>
                            </div>
                        </div>

                        <!-- Lectura Actual -->
                        <div class="mb-5">
                            <label class="form-label fw-bold text-dark text-uppercase small tracking-wider">Lectura Actual (m³)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 py-3"><i class="fas fa-tint text-info"></i></span>
                                <input type="number" name="lectura_actual" step="0.001" 
                                    class="form-control form-stellar border-start-0 py-3 fs-5" 
                                    placeholder="0.000" required>
                                <span class="input-group-text bg-light fw-bold">m³</span>
                            </div>
                            <div class="form-text mt-2 text-muted">
                                <i class="fas fa-info-circle me-1"></i> Ingrese los números del medidor (incluyendo decimales si aplica).
                            </div>
                        </div>

                        <!-- Botón de Acción Principal (Stellar Pill) -->
                        <div class="pt-4 border-top">
                            <button type="submit" class="btn btn-purple-stellar btn-lg w-100 shadow-sm py-3">
                                <i class="fas fa-save me-2"></i> Guardar y Generar Cobro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* VARIABLES Y ESTILOS ESPECÍFICOS */
    .text-purple { color: #5f4d93; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.05); }
    .border-purple { border-color: #5f4d93 !important; }
    .tracking-wider { letter-spacing: 1.5px; }

    /* Inputs Estilizados */
    .form-stellar {
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: 0.3s;
    }
    .form-stellar:focus {
        border-color: #5f4d93;
        box-shadow: 0 0 0 0.25rem rgba(95, 77, 147, 0.1);
    }

    /* Botón Stellar con Degradado */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(95, 77, 147, 0.3) !important;
    }

    /* Alertas Estilo Stellar */
    .alert-stellar {
        background: #fff;
        border-left: 5px solid #5f4d93;
        color: #5f4d93;
    }
    .alert-stellar-danger {
        background: #fff;
        border-left: 5px solid #e74c3c;
        color: #e74c3c;
    }

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