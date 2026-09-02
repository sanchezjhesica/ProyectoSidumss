@extends('layouts.admin')

@section('content')
<div class="tarifas-stellar">
    <!-- 1. CABECERA -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-coins me-2"></i> Configuración de Tarifas</h2>
            <p class="text-muted mb-0">Gestione los precios base de servicios, remesas y áreas comunes.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-stellar-success alert-dismissible fade show mb-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 2. NAVEGACIÓN POR PESTAÑAS (PILLS STYLE) -->
    <ul class="nav nav-pills mb-4" id="tarifasTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active btn-stellar-tab me-2 shadow-sm" id="global-tab" data-bs-toggle="pill" data-bs-target="#global" type="button" role="tab">
                <i class="fas fa-tint me-2"></i>Agua y Mantenimiento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-stellar-tab me-2 shadow-sm" id="remesas-tab" data-bs-toggle="pill" data-bs-target="#remesas" type="button" role="tab">
                <i class="fas fa-shield-alt me-2"></i>Remesas (Seguridad/Jardín)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-stellar-tab shadow-sm" id="areas-tab" data-bs-toggle="pill" data-bs-target="#areas" type="button" role="tab">
                <i class="fas fa-volleyball-ball me-2"></i>Alquiler de Áreas
            </button>
        </li>
    </ul>

    <!-- 3. CONTENIDO DE LAS PESTAÑAS -->
    <div class="tab-content border-0">
        
        <!-- SECCIÓN 1: AGUA Y MANTENIMIENTO -->
        <div class="tab-pane fade show active" id="global" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.tarifas.updateGlobal') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label-stellar">Precio m³ Agua (Bs.)</label>
                                <input type="number" step="0.01" name="precio_m3_agua" class="form-control form-stellar" value="{{ $tarifa->precio_m3_agua }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-stellar">Cuota Mantenimiento Fija (Bs.)</label>
                                <input type="number" step="0.01" name="monto_mantenimiento_fijo" class="form-control form-stellar" value="{{ $tarifa->monto_mantenimiento_fijo }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-stellar">Alcantarillado (Bs.)</label>
                                <input type="number" step="0.01" name="monto_alcantarillado" class="form-control form-stellar" value="{{ $tarifa->monto_alcantarillado }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-stellar">Porcentaje Mora (%)</label>
                                <input type="number" step="0.01" name="porcentaje_mora" class="form-control form-stellar" value="{{ $tarifa->porcentaje_mora }}">
                            </div>
                        </div>
                        <div class="mt-4 pt-3 text-end">
                            <button type="submit" class="btn btn-stellar-blue px-5 shadow">
                                <i class="fas fa-save me-2"></i> Actualizar Precios Globales
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: REMESAS -->
        <div class="tab-pane fade" id="remesas" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 uppercase-tracking">Concepto</th>
                                <th class="py-3 uppercase-tracking d-none d-md-table-cell">Descripción</th>
                                <th class="py-3 uppercase-tracking">Monto Actual</th>
                                <th class="py-3 text-center uppercase-tracking">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($remesas as $r)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $r->nombre_remesa }}</div>
                                </td>
                                <td class="small text-muted d-none d-md-table-cell">{{ $r->descripcion }}</td>
                                <form action="{{ route('admin.tarifas.updateRemesa', $r->id_remesa_config) }}" method="POST">
                                    @csrf @method('PUT')
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 130px;">
                                            <span class="input-group-text bg-white border-end-0">Bs.</span>
                                            <input type="number" step="0.01" name="monto_estandar" class="form-control border-start-0 fw-bold" value="{{ $r->monto_estandar }}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="submit" class="btn btn-sm btn-outline-stellar rounded-pill px-3">
                                            <i class="fas fa-sync-alt me-1"></i> Actualizar
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: ÁREAS RECREATIVAS -->
        <div class="tab-pane fade" id="areas" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 uppercase-tracking">Área / Espacio</th>
                                <th class="py-3 uppercase-tracking">Precio por Reserva</th>
                                <th class="py-3 text-center uppercase-tracking">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areas as $a)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-blue-soft text-stellar-blue me-3"><i class="fas fa-map-marker-alt"></i></div>
                                        <span class="fw-bold text-dark">{{ $a->nombre_area }}</span>
                                    </div>
                                </td>
                                <form action="{{ route('admin.tarifas.updateArea', $a->id_area) }}" method="POST">
                                    @csrf @method('PUT')
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <span class="input-group-text bg-white border-end-0">Bs.</span>
                                            <input type="number" step="0.01" name="costo_reserva" class="form-control border-start-0 fw-bold" value="{{ $a->costo_reserva }}">
                                        </div>
                                    </td>
                                    <td class="text-center pe-3">
                                        <button type="submit" class="btn btn-sm btn-outline-stellar rounded-pill px-3">
                                            <i class="fas fa-check me-1"></i> Actualizar
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    }

    .text-stellar-blue { color: var(--stellar-blue); }
    .bg-blue-soft { background-color: rgba(14, 92, 173, 0.08); }
    
    .uppercase-tracking {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #888;
    }

    /* Estilo de Pestañas (Pills) */
    .btn-stellar-tab {
        background-color: white;
        border: 1px solid #ddd;
        color: #555;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: var(--stellar-blue) !important;
        color: white !important;
        border-color: var(--stellar-blue);
        box-shadow: 0 4px 10px rgba(14, 92, 173, 0.2);
    }

    /* Inputs Estilo Stellar */
    .form-label-stellar {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #777;
        margin-bottom: 8px;
        display: block;
    }

    .form-stellar {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        background-color: #fdfdfd;
        transition: all 0.3s ease;
    }
    .form-stellar:focus {
        border-color: var(--stellar-blue);
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(14, 92, 173, 0.1);
    }

    .input-group-text {
        border: 1px solid #e0e0e0;
        color: #aaa;
    }

    /* Botones */
    .btn-stellar-blue {
        background: var(--stellar-button);
        color: white !important;
        border-radius: 50px;
        font-weight: 700;
        border: none;
        padding: 12px 30px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }
    .btn-stellar-blue:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(34, 52, 158, 0.3);
    }

    .btn-outline-stellar {
        border: 1px solid var(--stellar-blue);
        color: var(--stellar-blue);
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-outline-stellar:hover {
        background: var(--stellar-blue);
        color: white;
    }

    /* Alertas */
    .alert-stellar-success {
        background-color: #f0fff4;
        border-left: 5px solid #38a169;
        color: #276749;
        border-radius: 10px;
    }

    .icon-circle {
        width: 35px; height: 35px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }

    .rounded-4 { border-radius: 1.25rem !important; }

    @media (max-width: 768px) {
        .card-body { padding: 1.5rem !important; }
        .btn-stellar-tab { font-size: 0.8rem; padding: 8px 12px; }
    }
</style>
@endsection