@extends('layouts.app')

@section('title', 'Editar Producto: ' . $product->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Editar Producto</h1>
            <p class="mt-1 text-sm text-slate-500">Actualiza la información del producto #{{ $product->id }}.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre del Producto <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required maxlength="255" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none @error('name') border-rose-300 ring-rose-300 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div>
                <label for="description" class="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none @error('description') border-rose-300 ring-rose-300 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio y Stock -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-slate-700 mb-1">Precio ($) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-slate-400 text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" min="0" max="999999.99" name="price" id="price" value="{{ old('price', $product->price) }}" required class="w-full rounded-lg border border-slate-300 pl-7 pr-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none @error('price') border-rose-300 ring-rose-300 @enderror">
                    </div>
                    @error('price')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-slate-700 mb-1">Stock Disponible <span class="text-rose-500">*</span></label>
                    <input type="number" min="0" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none @error('stock') border-rose-300 ring-rose-300 @enderror">
                    @error('stock')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Estado -->
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Estado <span class="text-rose-500">*</span></label>
                <select name="status" id="status" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none @error('status') border-rose-300 ring-rose-300 @enderror">
                    <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Activo (Disponible para venta)</option>
                    <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactivo (Oculto)</option>
                </select>
                @error('status')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm transition">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
