<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/clientes.edit.css') }}">

    <x-slot name="header">
        <h2 class="page-header">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container-sm">

            <div class="card">
                <div class="card-content">

                    <form method="POST" action="{{ route('clientes.update', $cliente->idCliente) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">

                            {{-- CUIT --}}
                            <div class="form-group">
                                <label for="CUIT">CUIT</label>
                                <input type="text" id="CUIT" name="CUIT"
                                    value="{{ old('CUIT', $cliente->CUIT) }}" maxlength="11">
                                @error('CUIT') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Tipo Documento (Select) --}}
                            <div class="form-group">
                                <label for="TipoDocumento">Tipo Documento</label>
                                <select id="TipoDocumento" name="TipoDocumento" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="CUIT" {{ old('TipoDocumento', $cliente->TipoDocumento) == 'CUIT' ? 'selected' : '' }}>CUIT</option>
                                    <option value="CUIL" {{ old('TipoDocumento', $cliente->TipoDocumento) == 'CUIL' ? 'selected' : '' }}>CUIL</option>
                                    <option value="DNI" {{ old('TipoDocumento', $cliente->TipoDocumento) == 'DNI' ? 'selected' : '' }}>DNI</option>
                                    <option value="Pasaporte" {{ old('TipoDocumento', $cliente->TipoDocumento) == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                    <option value="Otro" {{ old('TipoDocumento', $cliente->TipoDocumento) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('TipoDocumento') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Nro Documento --}}
                            <div class="form-group">
                                <label for="NroDocumento">Nro Documento</label>
                                <input type="text" id="NroDocumento" name="NroDocumento"
                                    value="{{ old('NroDocumento', $cliente->NroDocumento) }}">
                                @error('NroDocumento') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Condición frente al IVA (Select) --}}
                            <div class="form-group">
                                <label for="CondicionIVA">Condición frente al IVA</label>
                                <select id="CondicionIVA" name="CondicionIVA" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Responsable Inscripto" {{ old('CondicionIVA', $cliente->CondicionIVA) == 'Responsable Inscripto' ? 'selected' : '' }}>Responsable Inscripto</option>
                                    <option value="Monotributista" {{ old('CondicionIVA', $cliente->CondicionIVA) == 'Monotributista' ? 'selected' : '' }}>Monotributista</option>
                                    <option value="Consumidor Final" {{ old('CondicionIVA', $cliente->CondicionIVA) == 'Consumidor Final' ? 'selected' : '' }}>Consumidor Final</option>
                                    <option value="Exento" {{ old('CondicionIVA', $cliente->CondicionIVA) == 'Exento' ? 'selected' : '' }}>Exento</option>
                                    <option value="No Responsable" {{ old('CondicionIVA', $cliente->CondicionIVA) == 'No Responsable' ? 'selected' : '' }}>No Responsable</option>
                                </select>
                                @error('CondicionIVA') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Nro Ingresos Brutos --}}
                            <div class="form-group">
                                <label for="NroIngBrutos">Nro Ingresos Brutos</label>
                                <input type="text" id="NroIngBrutos" name="NroIngBrutos"
                                    value="{{ old('NroIngBrutos', $cliente->NroIngBrutos) }}">
                                @error('NroIngBrutos') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Condición Ingresos Brutos (Select) --}}
                            <div class="form-group">
                                <label for="CondicionIngBrutos">Condición Ingresos Brutos</label>
                                <select id="CondicionIngBrutos" name="CondicionIngBrutos" class="form-control">
                                    <option value="">Seleccione...</option>
                                    <option value="Local" {{ old('CondicionIngBrutos', $cliente->CondicionIngBrutos) == 'Local' ? 'selected' : '' }}>Local</option>
                                    <option value="Multilateral" {{ old('CondicionIngBrutos', $cliente->CondicionIngBrutos) == 'Multilateral' ? 'selected' : '' }}>Multilateral</option>
                                    <option value="Exento" {{ old('CondicionIngBrutos', $cliente->CondicionIngBrutos) == 'Exento' ? 'selected' : '' }}>Exento</option>
                                    <option value="No Responsable" {{ old('CondicionIngBrutos', $cliente->CondicionIngBrutos) == 'No Responsable' ? 'selected' : '' }}>No Responsable</option>
                                </select>
                                @error('CondicionIngBrutos') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Responsabilidad Social --}}
                            <div class="form-group">
                                <label for="ResponsabilidadSocial">Responsabilidad Social</label>
                                <select id="ResponsabilidadSocial" name="ResponsabilidadSocial" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <option value="Física" {{ old('ResponsabilidadSocial', $cliente->ResponsabilidadSocial) == 'Física' ? 'selected' : '' }}>Física</option>
                                    <option value="Jurídica" {{ old('ResponsabilidadSocial', $cliente->ResponsabilidadSocial) == 'Jurídica' ? 'selected' : '' }}>Jurídica</option>
                                </select>
                                @error('ResponsabilidadSocial') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Razón Social --}}
                            <div class="form-group">
                                <label for="RazonSocial">Razón Social</label>
                                <input type="text" id="RazonSocial" name="RazonSocial"
                                    value="{{ old('RazonSocial', $cliente->RazonSocial) }}" required>
                                @error('RazonSocial') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Límite de Crédito --}}
                            <div class="form-group">
                                <label for="LimiteCredito">Límite de Crédito</label>
                                <input type="number" id="LimiteCredito" name="LimiteCredito" min="0" step="0.01"
                                    value="{{ old('LimiteCredito', $cliente->LimiteCredito) }}">
                                @error('LimiteCredito') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Activo --}}
                            <div class="form-group">
                                <label for="Activo">Activo</label>
                                <input type="checkbox" id="Activo" name="Activo"
                                    {{ old('Activo', $cliente->Activo) ? 'checked' : '' }}>
                                @error('Activo') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <label for="Email">Email</label>
                                <input type="email" id="Email" name="Email"
                                    value="{{ old('Email', $cliente->Email) }}">
                                @error('Email') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="form-group">
                                <label for="Telefono">Teléfono</label>
                                <input type="text" id="Telefono" name="Telefono"
                                    value="{{ old('Telefono', $cliente->Telefono) }}">
                                @error('Telefono') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Domicilio Fiscal --}}
                            <div class="form-group">
                                <label for="DomicilioFiscal">Domicilio Fiscal</label>
                                <input type="text" id="DomicilioFiscal" name="DomicilioFiscal"
                                    value="{{ old('DomicilioFiscal', $cliente->DomicilioFiscal) }}">
                                @error('DomicilioFiscal') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Localidad --}}
                            <div class="form-group">
                                <label for="Localidad">Localidad</label>
                                <input type="text" id="Localidad" name="Localidad"
                                    value="{{ old('Localidad', $cliente->Localidad) }}">
                                @error('Localidad') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Provincia --}}
                            <div class="form-group">
                                <label for="Provincia">Provincia</label>
                                <input type="text" id="Provincia" name="Provincia"
                                    value="{{ old('Provincia', $cliente->Provincia) }}">
                                @error('Provincia') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Código Postal --}}
                            <div class="form-group">
                                <label for="CodigoPostal">Código Postal</label>
                                <input type="text" id="CodigoPostal" name="CodigoPostal"
                                    value="{{ old('CodigoPostal', $cliente->CodigoPostal) }}">
                                @error('CodigoPostal') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- País --}}
                            <div class="form-group">
                                <label for="Pais">País</label>
                                <input type="text" id="Pais" name="Pais"
                                    value="{{ old('Pais', $cliente->Pais) }}">
                                @error('Pais') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Observaciones --}}
                            <div class="form-group full-width">
                                <label for="Observaciones">Observaciones</label>
                                <textarea id="Observaciones" name="Observaciones" rows="3">{{ old('Observaciones', $cliente->Observaciones) }}</textarea>
                                @error('Observaciones') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                            {{-- Fecha Alta --}}
                            <div class="form-group">
                                <label for="FechaAlta">Fecha Alta</label>
                                <input type="date" id="FechaAlta" name="FechaAlta"
                                    value="{{ old('FechaAlta', $cliente->FechaAlta ? $cliente->FechaAlta->format('Y-m-d') : '') }}">
                                @error('FechaAlta') <span class="error-message">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        {{-- Botones --}}
                        <div class="form-actions mt-6">
                            <button type="submit" class="btn-primary">Actualizar</button>
                            <a href="{{ route('clientes.index') }}" class="btn-secondary">Cancelar</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
