@extends('layouts.propietario')

@section('content')
<div class="propietario-stellar">
    <!-- 1. CABECERA DE SECCIÓN -->
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
        <div>
            <h2 class="text-stellar-blue mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2"></i> Mis Avisos de Cobro</h2>
            <p class="text-muted mb-0">Consulte, descargue y pague sus avisos mensuales.</p>
        </div>
    </div>

    <!-- 2. NAVEGACIÓN POR PESTAÑAS (TABS) -->
    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active btn-stellar-tab me-2 shadow-sm" id="pills-agua-tab" data-bs-toggle="pill" data-bs-target="#pills-agua" type="button" role="tab">
                <i class="fas fa-tint me-2"></i> 1. Agua Potable
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-stellar-tab me-2 shadow-sm" id="pills-mantenimiento-tab" data-bs-toggle="pill" data-bs-target="#pills-mantenimiento" type="button" role="tab">
                <i class="fas fa-tools me-2"></i> 2. Mantenimiento
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link btn-stellar-tab shadow-sm" id="pills-remesas-tab" data-bs-toggle="pill" data-bs-target="#pills-remesas" type="button" role="tab">
                <i class="fas fa-hand-holding-usd me-2"></i> 3. Expensas / Remesas
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <!-- SECCIÓN 1: AGUA -->
        <div class="tab-pane fade show active" id="pills-agua" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 uppercase-tracking">Periodo</th>
                                <th class="py-3 uppercase-tracking">Total</th>
                                <th class="py-3 text-center uppercase-tracking">Estado</th>
                                <th class="py-3 text-center uppercase-tracking">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($avisosAgua as $a)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::create()->month($a->mes)->translatedFormat('F') }} {{ $a->anio }}</div>
                                </td>
                                <td><span class="fw-bold">Bs. {{ number_format($a->total_pagar, 2) }}</span></td>
                                <td class="text-center">
                                    <span class="badge-stellar {{ $a->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                        {{ $a->estado_pago }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('propietario.descargar.agua', $a->id_cobro_agua) }}" class="btn btn-view-stellar">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                        @if($a->estado_pago != 'Pagado')
                                        <button class="btn btn-qr-stellar" data-bs-toggle="modal" data-bs-target="#modalQR" data-id="{{ $a->id_cobro_agua }}" data-tipo="agua">
                                            <i class="fas fa-qrcode"></i> Pagar QR
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4">No hay avisos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: MANTENIMIENTO -->
        <div class="tab-pane fade" id="pills-mantenimiento" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Periodo</th>
                            <th>Monto</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($avisosMantenimiento as $m)
                        <tr>
                            <td class="ps-4"><b>{{ \Carbon\Carbon::create()->month($m->mes)->translatedFormat('F') }} {{ $m->anio }}</b></td>
                            <td>Bs. {{ number_format($m->monto_fijo, 2) }}</td>
                            <td class="text-center">
                                <span class="badge-stellar {{ $m->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                    {{ $m->estado_pago }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('propietario.descargar.mantenimiento', $m->id_cobro_mantenimiento) }}" class="btn btn-view-stellar">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    @if($m->estado_pago != 'Pagado')
                                    <button class="btn btn-qr-stellar" data-bs-toggle="modal" data-bs-target="#modalQR" data-id="{{ $m->id_cobro_mantenimiento }}" data-tipo="mantenimiento">
                                        <i class="fas fa-qrcode"></i> Pagar QR
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No hay cobros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 3: REMESAS -->
        <div class="tab-pane fade" id="pills-remesas" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Aviso</th>
                            <th>Periodo</th>
                            <th>Total</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($avisosRemesas as $r)
                        <tr>
                            <td class="ps-4 small">Planilla de Expensas</td>
                            <td>{{ \Carbon\Carbon::create()->month($r->mes)->translatedFormat('F') }} {{ $r->anio }}</td>
                            <td><span class="fw-bold">Bs. {{ number_format($r->total_mes, 2) }}</span></td>
                            <td class="text-center">
                                <span class="badge-stellar {{ $r->estado_pago == 'Pagado' ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }}">
                                    {{ $r->estado_pago }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('propietario.descargar.remesas', $r->id_referencia) }}" class="btn btn-view-stellar">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                    @if($r->estado_pago != 'Pagado')
                                    <button class="btn btn-qr-stellar" data-bs-toggle="modal" data-bs-target="#modalQR" data-id="{{ $r->id_referencia }}" data-tipo="remesas">
                                        <i class="fas fa-qrcode"></i> Pagar QR
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4">No hay remesas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL QR (UBICADO FUERA DEL CONTENEDOR PRINCIPAL) -->
<div class="modal fade" id="modalQR" tabindex="-1" aria-labelledby="modalQRLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-stellar-blue" id="modalQRLabel">Pagar con QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <p class="text-muted small">Escanee y realice la transferencia desde su banco.</p>
                    
                    @if($config && $config->qr_pago)
                        <!-- Muestra el QR subido por el administrador -->
                        <img src="{{ asset('storage/' . $config->qr_pago) }}" 
                            class="img-fluid rounded-3 border shadow-sm" 
                            style="max-width: 180px; height: auto;" 
                            alt="QR de Pago Actual">
                    @else
                        <!-- Imagen por defecto si el admin no ha subido nada todavía -->
                        <img src="{{ asset('img/logo.jpg') }}" 
                            class="img-fluid rounded-3 border shadow-sm opacity-50" 
                            style="max-width: 160px; height: auto;" 
                            alt="Sin QR disponible">
                        <p class="text-danger small mt-2">QR no disponible temporalmente</p>
                    @endif
                </div>

                <hr class="opacity-10">

                <!-- CAMBIO: Agregamos la ruta correcta en el action -->
                <form action="{{ route('propietario.comprobante.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_pago" id="input_id_pago">
                    <input type="hidden" name="tipo_pago" id="input_tipo_pago">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small text-uppercase letter-spacing-1">
                            <i class="fas fa-upload me-2"></i>Adjuntar Comprobante
                        </label>
                        <input type="file" name="comprobante" class="form-control form-stellar" accept="image/*" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-pill w-50 fw-bold" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-stellar-submit w-50 py-2 fw-bold">Enviar Pago</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* CORRECCIÓN DEL Z-INDEX PARA QUE EL MODAL SOBREPASE LA CAPA OSCURA */

    /* Estilos generales */
    :root { 
        --stellar-blue: #0e5cad; 
        --stellar-grad: linear-gradient(45deg, #22349e 0%, #8183e6 100%); 
    }
    
    .text-stellar-blue { color: var(--stellar-blue); }
    .letter-spacing-1 { letter-spacing: 1px; }
    .bg-success-soft { background-color: rgba(40, 167, 69, 0.1); }
    .bg-danger-soft { background-color: rgba(220, 53, 69, 0.1); }
    
    .btn-stellar-tab { background-color: white; border: 1px solid #ddd; color: #555; border-radius: 12px; padding: 10px 20px; font-weight: 600; }
    .nav-pills .nav-link.active { background-color: var(--stellar-blue) !important; color: white !important; }

    .form-stellar { border-radius: 10px; border: 1px solid #ddd; padding: 12px; font-size: 0.9rem; background: #fdfdfd; }
    .btn-stellar-submit { background: var(--stellar-grad); color: white; border: none; border-radius: 50px; transition: 0.3s; text-transform: uppercase; font-size: 0.8rem; }
    .btn-stellar-submit:hover { opacity: 0.9; transform: scale(1.02); }

    .btn-view-stellar { background-color: white; border: 1px solid var(--stellar-blue); color: var(--stellar-blue); border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; text-decoration: none; transition: 0.3s; }
    .btn-qr-stellar { background-color: #f39c12; border: none; color: white; border-radius: 50px; padding: 4px 12px; font-size: 0.75rem; font-weight: 600; transition: 0.3s; }

    .badge-stellar { display: inline-block; padding: 5px 14px; border-radius: 50px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .rounded-4 { border-radius: 1rem !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalQR = document.getElementById('modalQR');
        
        if(modalQR) {
            // Solución: Mueve el modal al <body> para romper el contexto de apilamiento
            document.body.appendChild(modalQR);

            modalQR.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                document.getElementById('input_id_pago').value = button.getAttribute('data-id');
                document.getElementById('input_tipo_pago').value = button.getAttribute('data-tipo');
            });
        }
    });
</script>
@endsection