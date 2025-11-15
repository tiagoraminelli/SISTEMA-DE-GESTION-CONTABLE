<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/cuentas_contables.edit.css') }}">

    <x-slot name="header">
        <h2 class="page-header">{{ __('Editar Cuenta Contable') }}</h2>
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
                            setTimeout(() => dialog.close(), 3000);
                        }
                    });
                </script>
            @endif

            <div class="card">
                <div class="card-content">

                    <form method="POST" action="{{ route('cuentas_contables.update', $cuenta->idCuentaContable) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            {{-- Código --}}
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" id="codigo" name="codigo" value="{{ old('codigo', $cuenta->codigo) }}" required class="form-input @error('codigo') is-invalid @enderror">
                                @error('codigo')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nombre --}}
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $cuenta->nombre) }}" required class="form-input @error('nombre') is-invalid @enderror">
                                @error('nombre')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Tipo --}}
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select id="tipo" name="tipo" required class="form-input @error('tipo') is-invalid @enderror">
                                    @foreach(['Activo','Pasivo','Patrimonio Neto','Resultado Positivo','Resultado Negativo'] as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tipo', $cuenta->tipo) === $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
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
                                        <option value="{{ $subtipo }}" {{ old('subtipo', $cuenta->subtipo) === $subtipo ? 'selected' : '' }}>{{ $subtipo }}</option>
                                    @endforeach
                                </select>
                                @error('subtipo')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Rubro --}}
                            <div class="form-group">
                                <label for="rubro">Rubro</label>
                                <input type="text" id="rubro" name="rubro" value="{{ old('rubro', $cuenta->rubro) }}" class="form-input @error('rubro') is-invalid @enderror">
                                @error('rubro')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Nivel --}}
                            <div class="form-group">
                                <label for="nivel">Nivel</label>
                                <input type="number" id="nivel" name="nivel" value="{{ old('nivel', $cuenta->nivel) }}" min="1" max="10" class="form-input @error('nivel') is-invalid @enderror">
                                @error('nivel')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Estado --}}
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select id="estado" name="estado" class="form-input @error('estado') is-invalid @enderror">
                                    <option value="1" {{ old('estado', $cuenta->estado) ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !old('estado', $cuenta->estado) ? 'selected' : '' }}>Inactivo</option>
                                </select>
                                @error('estado')<p class="error-message">{{ $message }}</p>@enderror
                            </div>

                            {{-- Descripción --}}
                            <div class="form-group full-width">
                                <label for="descripcion">Descripción</label>
                                <textarea id="descripcion" name="descripcion" class="form-input @error('descripcion') is-invalid @enderror">{{ old('descripcion', $cuenta->descripcion) }}</textarea>
                                @error('descripcion')<p class="error-message">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="form-actions mt-4">
                            <button type="submit" class="btn-create">Actualizar Cuenta</button>
                            <a href="{{ route('cuentas_contables.index') }}" class="reset-button">Cancelar y Volver</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
    </style>
</x-app-layout>
