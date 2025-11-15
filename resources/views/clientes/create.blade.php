<style>
    /* ==================================================
    CSS Nativo Minimalista para Formularios
    ==================================================
    */
    :root {
        --color-primary: #3b82f6;
        /* Azul */
        --color-primary-dark: #2563eb;
        --color-text-dark: #1f2937;
        /* Texto oscuro */
        --color-bg-light: #ffffff;
        /* Fondo claro */
        --color-border: #d1d5db;
        /* Borde de campo */
        --color-error: #ef4444;
        /* Rojo para errores */
        --color-label: #4b5563;
        /* Gris para etiquetas */
        --color-save: #10b981;
        /* Verde para Guardar */
        --color-save-hover: #059669;
        --color-cancel: #6b7280;
        /* Gris para Cancelar/Volver */
        --color-cancel-hover: #4b5563;
    }

    /* Layout General */
    .py-12 {
        padding-top: 3rem;
        padding-bottom: 3rem;
    }

    .container-sm {
        max-width: 900px;
        /* Un poco más ancho para 3 columnas */
        margin-left: auto;
        margin-right: auto;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    /* Header */
    .page-header {
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1.75rem;
        color: var(--color-text-dark);
    }

    /* Card/Content Box */
    .card {
        background-color: var(--color-bg-light);
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
    }

    .card-content {
        padding: 1.5rem;
        color: var(--color-text-dark);
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        /* Columna única por defecto */
        gap: 1.5rem;
    }

    /* Layout de tres columnas en pantallas medianas y grandes */
    @media (min-width: 768px) {
        .form-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Estilo de cada grupo de formulario */
    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: var(--color-label);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    /* Estilo de Input/Select/Textarea */
    .form-control {
        padding: 0.6rem 0.75rem;
        border: 1px solid var(--color-border);
        border-radius: 0.25rem;
        font-size: 1rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: var(--color-primary);
        outline: 0;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        /* Sombra de foco azul suave */
    }

    .form-control[type="checkbox"] {
        width: auto;
    }

    /* Input Group para los que ocupan el ancho completo */
    .full-width {
        grid-column: 1 / -1;
    }

    /* Contenedor de Checkbox/Radio */
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
    }

    .checkbox-container input[type="checkbox"] {
        margin-right: 0.5rem;
        width: 1rem;
        height: 1rem;
    }

    /* Errores de validación */
    .error-message {
        color: var(--color-error);
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    /* Botones de acción */
    .action-buttons {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        /* Ocupa el ancho completo al final del formulario */
        grid-column: 1 / -1;
    }

    .btn {
        padding: 0.6rem 1.25rem;
        border-radius: 0.25rem;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }

    .btn-save {
        background-color: var(--color-save);
        color: var(--color-bg-light);
    }

    .btn-save:hover {
        background-color: var(--color-save-hover);
    }

    .btn-cancel {
        background-color: var(--color-cancel);
        color: var(--color-bg-light);
    }

    .btn-cancel:hover {
        background-color: var(--color-cancel-hover);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="page-header">
            {{ __('Crear Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container-sm">

            <div class="card">
                <div class="card-content">

                    {{-- Formulario de Creación --}}
                    <form method="POST" action="{{ route('clientes.store') }}">
                        @csrf

                        <div class="form-grid">

                            {{-- Columna 1 --}}
                            <div class="form-group full-width">
                                <label for="RazonSocial">Razón Social <span
                                        style="color:var(--color-error)">*</span></label>
                                <input type="text" id="RazonSocial" name="RazonSocial" class="form-control"
                                    value="{{ old('RazonSocial') }}" required>
                                @error('RazonSocial')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="CUIT">CUIT/NIF</label>
                                <input type="text" id="CUIT" name="CUIT" class="form-control"
                                    value="{{ old('CUIT') }}">
                                @error('CUIT')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="TipoDocumento">Tipo de Documento</label>
                                <select id="TipoDocumento" name="TipoDocumento" class="form-control">
                                    <option value="" disabled {{ old('TipoDocumento') ? '' : 'selected' }}>
                                        Selecciona Tipo</option>
                                    <option value="CUIT" {{ old('TipoDocumento') == 'CUIT' ? 'selected' : '' }}>CUIT
                                    </option>
                                    <option value="CUIL" {{ old('TipoDocumento') == 'CUIL' ? 'selected' : '' }}>CUIL
                                    </option>
                                    <option value="DNI" {{ old('TipoDocumento') == 'DNI' ? 'selected' : '' }}>DNI
                                    </option>
                                    <option value="Pasaporte"
                                        {{ old('TipoDocumento') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                    <option value="Otro" {{ old('TipoDocumento') == 'Otro' ? 'selected' : '' }}>Otro
                                    </option>
                                </select>
                                @error('TipoDocumento')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="NroDocumento">Nro Documento</label>
                                <input type="text" id="NroDocumento" name="NroDocumento" class="form-control"
                                    value="{{ old('NroDocumento') }}">
                                @error('NroDocumento')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Condición frente al IVA --}}
                            <div class="form-group">
                                <label for="CondicionIVA">Condición frente al IVA</label>
                                <select id="CondicionIVA" name="CondicionIVA" class="form-control">
                                    <option value="" disabled {{ old('CondicionIVA') ? '' : 'selected' }}>
                                        Selecciona Condición</option>
                                    <option value="Responsable Inscripto"
                                        {{ old('CondicionIVA') == 'Responsable Inscripto' ? 'selected' : '' }}>
                                        Responsable Inscripto</option>
                                    <option value="Monotributista"
                                        {{ old('CondicionIVA') == 'Monotributista' ? 'selected' : '' }}>Monotributista
                                    </option>
                                    <option value="Consumidor Final"
                                        {{ old('CondicionIVA') == 'Consumidor Final' ? 'selected' : '' }}>Consumidor
                                        Final</option>
                                    <option value="Exento" {{ old('CondicionIVA') == 'Exento' ? 'selected' : '' }}>
                                        Exento</option>
                                </select>
                                @error('CondicionIVA')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="NroIngBrutos">Nro Ingresos Brutos</label>
                                <input type="text" id="NroIngBrutos" name="NroIngBrutos" class="form-control"
                                    value="{{ old('NroIngBrutos') }}">
                                @error('NroIngBrutos')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Condición Ingresos Brutos --}}
                            <div class="form-group">
                                <label for="CondicionIngBrutos">Condición Ingresos Brutos</label>
                                <select id="CondicionIngBrutos" name="CondicionIngBrutos" class="form-control">
                                    <option value="" disabled {{ old('CondicionIngBrutos') ? '' : 'selected' }}>
                                        Selecciona Condición</option>
                                    <option value="Local"
                                        {{ old('CondicionIngBrutos') == 'Local' ? 'selected' : '' }}>Local</option>
                                    <option value="Multilateral"
                                        {{ old('CondicionIngBrutos') == 'Multilateral' ? 'selected' : '' }}>
                                        Multilateral</option>
                                </select>
                                @error('CondicionIngBrutos')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="LimiteCredito">Límite de Crédito ($)</label>
                                <input type="number" step="0.01" id="LimiteCredito" name="LimiteCredito"
                                    class="form-control" value="{{ old('LimiteCredito', 0.0) }}">
                                @error('LimiteCredito')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Email">Email</label>
                                <input type="email" id="Email" name="Email" class="form-control"
                                    value="{{ old('Email') }}">
                                @error('Email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Telefono">Teléfono</label>
                                <input type="text" id="Telefono" name="Telefono" class="form-control"
                                    value="{{ old('Telefono') }}">
                                @error('Telefono')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Columna 3 (Domicilio) --}}
                            <div class="form-group">
                                <label for="DomicilioFiscal">Domicilio Fiscal</label>
                                <input type="text" id="DomicilioFiscal" name="DomicilioFiscal"
                                    class="form-control" value="{{ old('DomicilioFiscal') }}">
                                @error('DomicilioFiscal')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Localidad">Localidad</label>
                                <input type="text" id="Localidad" name="Localidad" class="form-control"
                                    value="{{ old('Localidad') }}">
                                @error('Localidad')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Provincia">Provincia</label>
                                <input type="text" id="Provincia" name="Provincia" class="form-control"
                                    value="{{ old('Provincia') }}">
                                @error('Provincia')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="CodigoPostal">Código Postal</label>
                                <input type="text" id="CodigoPostal" name="CodigoPostal" class="form-control"
                                    value="{{ old('CodigoPostal') }}">
                                @error('CodigoPostal')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="Pais">País</label>
                                <input type="text" id="Pais" name="Pais" class="form-control"
                                    value="{{ old('Pais') ?? 'Argentina' }}">
                                @error('Pais')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Checkboxes que no se ven bien en 3 columnas --}}
                            <div class="form-group">
                                <label>Estado del Cliente</label>
                                <div class="checkbox-container">
                                    <input type="checkbox" id="Activo" name="Activo" value="1"
                                        {{ old('Activo', 1) ? 'checked' : '' }}>
                                    <label for="Activo" style="margin-bottom: 0;">Cliente Activo</label>
                                </div>
                                @error('Activo')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Responsabilidad Social --}}
                            <div class="form-group">
                                <label>Responsabilidad Social</label>
                                <div class="checkbox-container">
                                    <input type="checkbox" id="EsJuridico" name="ResponsabilidadSocial"
                                        value="Jurídica"
                                        {{ old('ResponsabilidadSocial') == 'Jurídica' ? 'checked' : '' }}>
                                    <label for="EsJuridico" style="margin-bottom: 0;">Jurídica (Si se marca)</label>
                                </div>
                                @error('ResponsabilidadSocial')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Textarea para Observaciones (full width) --}}
                            <div class="form-group full-width">
                                <label for="Observaciones">Observaciones</label>
                                <textarea id="Observaciones" name="Observaciones" rows="3" class="form-control">{{ old('Observaciones') }}</textarea>
                                @error('Observaciones')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Botones de Acción (full width) --}}
                            <div class="action-buttons">
                                <button type="submit" class="btn btn-save">Guardar Cliente</button>
                                <a href="{{ route('clientes.index') }}" class="btn btn-cancel">Cancelar / Volver</a>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
