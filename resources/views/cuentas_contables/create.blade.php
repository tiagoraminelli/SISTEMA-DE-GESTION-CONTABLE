<x-app-layout>
    {{-- Enlaza el mismo CSS de edición ya que contiene los estilos de grid y formulario --}}
    <link rel="stylesheet" href="{{ asset('css/cuentas_contables.edit.css') }}">

    <x-slot name="header">
        <h2 class="page-header">{{ __('Crear Nueva Cuenta Contable') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="container">

            {{-- Modal nativo para mensajes de éxito --}}
            @if (session('success'))
                <dialog id="successDialog" class="success-dialog">
                    <div class="dialog-content">
                        <h3>✅ Éxito</h3>
                        <p>{{ session('success') }}</p>
                        <div class="dialog-actions">
                            <button type="button" id="closeSuccess" class="btn-close-dialog">Cerrar</button>
                        </div>
                    </div>
                </dialog>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const dialog = document.getElementById('successDialog');
                        const closeBtn = document.getElementById('closeSuccess');
                        if (dialog && typeof dialog.showModal === 'function') {
                            dialog.showModal();
                            closeBtn.addEventListener('click', () => dialog.close());
                            dialog.addEventListener('click', (e) => {
                                const rect = dialog.getBoundingClientRect();
                                const isInDialog = (
                                    e.clientX >= rect.left &&
                                    e.clientX <= rect.right &&
                                    e.clientY >= rect.top &&
                                    e.clientY <= rect.bottom
                                );
                                if (!isInDialog) dialog.close();
                            });
                            // Opcional: Cerrar automáticamente después de 3 segundos
                            setTimeout(() => dialog.close(), 3000);
                        }
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-content">

                    {{-- Formulario para la creación de una nueva cuenta contable --}}
                    <form method="POST" action="{{ route('cuentas_contables.store') }}">
                        @csrf
                        {{-- Se omite @method('PUT') ya que es una acción STORE --}}

                        <div class="form-grid">

                            {{-- Código --}}
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" id="codigo" name="codigo" value="{{ old('codigo') }}" required class="form-input @error('codigo') is-invalid @enderror">
                                @error('codigo')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nombre --}}
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required class="form-input @error('nombre') is-invalid @enderror">
                                @error('nombre')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Tipo --}}
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select id="tipo" name="tipo" required class="form-input @error('tipo') is-invalid @enderror">
                                    <option value="">--Seleccione--</option>
                                    @foreach(['Activo','Pasivo','Patrimonio Neto','Resultado Positivo','Resultado Negativo'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo') === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                                @error('tipo')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Subtipo --}}
                            <div class="form-group">
                                <label for="subtipo">Subtipo</label>
                                <select id="subtipo" name="subtipo" class="form-input @error('subtipo') is-invalid @enderror">
                                    <option value="">--Seleccione--</option>
                                    @foreach(['Corriente','No Corriente'] as $subtipo)
                                        <option value="{{ $subtipo }}" {{ old('subtipo') === $subtipo ? 'selected' : '' }}>{{ $subtipo }}</option>
                                    @endforeach
                                </select>
                                @error('subtipo')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Rubro --}}
                            <div class="form-group">
                                <label for="rubro">Rubro</label>
                                <input type="text" id="rubro" name="rubro" value="{{ old('rubro') }}" class="form-input @error('rubro') is-invalid @enderror">
                                @error('rubro')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nivel --}}
                            <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <input type="number" id="nivel" name="nivel" value="{{ old('nivel', 1) }}" min="1" max="10" class="form-input @error('nivel') is-invalid @enderror">
                                @error('nivel')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Estado --}}
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select id="estado" name="estado" class="form-input @error('estado') is-invalid @enderror">
                                    {{-- Valor por defecto en creación suele ser Activo (1) --}}
                                    <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ old('estado') === '0' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Placeholder para mantener la simetría si es necesario, o se deja vacío --}}
                            <div class="form-group">
                                {{-- Este espacio se puede usar para otro campo o como relleno --}}
                            </div>

                            {{-- Descripción (Ocupa ambas columnas) --}}
                            <div class="form-group full-width">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-input @error('descripcion') is-invalid @enderror">{{ old('descripcion') }}</textarea>
                                @error('descripcion')<p class="error-message">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="submit" class="btn-create">Guardar Cuenta</button>
                            <a href="{{ route('cuentas_contables.index') }}" class="reset-button">Cancelar y Volver</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
