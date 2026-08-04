@extends('layouts.propietario')

@section('content')
<div class="propietario-stellar">
    <!-- 1. SECCIÓN DE BIENVENIDA (HERO) -->
    <div class="text-center py-5 mb-5 border-bottom">
        <div class="user-avatar-large mx-auto mb-4">
            <i class="fas fa-user-circle"></i>
        </div>
        <h1 class="display-5 fw-bold text-dark">Bienvenido, {{ Auth::user()->nombre }}</h1>
        <p class="text-muted fs-5 mx-auto" style="max-width: 700px;">
            Desde su panel personal puede gestionar sus avisos de cobranza, revisar consumos de agua y realizar reservas para las áreas recreativas de la urbanización.
        </p>
        
        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="{{ route('propietario.avisos') }}" class="btn btn-purple-stellar btn-lg px-5 shadow-sm">
                <i class="fas fa-file-invoice-dollar me-2"></i> Ver Mis Avisos
            </a>
            <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline-purple btn-lg px-5 rounded-pill">
                <i class="fas fa-calendar-plus me-2"></i> Reservar Áreas
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- 2. MIS PROPIEDADES (Lista Estilizada) -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-purple"><i class="fas fa-home me-2"></i> Mis Propiedades Registradas</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">Usted figura como responsable de las siguientes unidades habitacionales:</p>
                    
                    @foreach($viviendas as $v)
                        <div class="property-item d-flex align-items-center p-3 mb-3 rounded-3 bg-light-soft border">
                            <div class="property-icon-box me-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark fs-6">Casa #{{ $v->nro_casa }}</div>
                                <div class="small text-muted">Ubicación: {{ $v->calle ?? 'Urbanización Central' }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge rounded-pill bg-purple-soft text-purple px-3">
                                    Medidor: {{ $v->nro_medidor }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 3. ACCESOS RÁPIDOS O INFO ADICIONAL -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-purple-stellar text-white">
                <div class="card-body p-5 d-flex flex-column justify-content-center text-center">
                    <i class="fas fa-info-circle fa-3x mb-4 opacity-50"></i>
                    <h4 class="fw-bold mb-3">¿Sabía que...?</h4>
                    <p class="mb-4 opacity-75">
                        Puede descargar sus recibos en formato PDF para imprimirlos o guardarlos como comprobante legal de sus pagos realizados.
                    </p>
                    <div class="mt-auto">
                        <small class="fw-bold text-uppercase tracking-widest">SIDUMSS · Gestión Responsable</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* VARIABLES Y ESTILOS PROPIETARIO STELLAR */
    .text-purple { color: #5f4d93; }
    .bg-purple-soft { background-color: rgba(95, 77, 147, 0.08); }
    .bg-light-soft { background-color: #fcfcfc; }
    .tracking-widest { letter-spacing: 2px; font-size: 0.7rem; }

    /* Avatar del Hero */
    .user-avatar-large {
        width: 80px;
        height: 80px;
        background: rgba(95, 77, 147, 0.1);
        color: #5f4d93;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
    }

    /* Botones Estilo Stellar */
    .btn-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
        color: white;
        border-radius: 50px;
        font-weight: bold;
        border: none;
        transition: 0.3s;
    }
    .btn-purple-stellar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(95, 77, 147, 0.3);
        color: white;
    }

    .btn-outline-purple {
        border: 2px solid #5f4d93;
        color: #5f4d93;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-outline-purple:hover {
        background-color: #5f4d93;
        color: white;
    }

    /* Items de Propiedad */
    .property-icon-box {
        width: 40px;
        height: 40px;
        background: white;
        color: #5f4d93;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .property-item {
        transition: all 0.3s;
    }
    .property-item:hover {
        border-color: #5f4d93 !important;
        background-color: #fff;
    }

    .bg-purple-stellar {
        background: linear-gradient(45deg, #5f4d93 0%, #e37682 100%);
    }

    .rounded-4 { border-radius: 1.25rem !important; }
</style>
@endsection