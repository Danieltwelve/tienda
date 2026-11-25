@extends('layouts.app')

@section('title', 'Detalle: ' . $product->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Detalles del Producto</h1>
            <p class="mt-1 text-sm text-slate-500">Información completa del registro #{{ $product->id }}.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a la lista
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 sm:p-8 space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-xs font-mono font-medium text-indigo-600 uppercase tracking-wider">Producto #{{ $product->id }}</span>
                    <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ $product->name }}</h2>
                </div>
                <div>
                    @if ($product->status === 'active')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> Inactivo
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div>
                    <span class="text-xs font-medium text-slate-500 uppercase">Precio</span>
                    <div class="text-3xl font-extrabold text-slate-900 mt-1">
                        ${{ number_format($product->price, 2) }}
                    </div>
                </div>
                <div>
                    <span class="text-xs font-medium text-slate-500 uppercase">Disponibilidad</span>
                    <div class="text-xl font-semibold text-slate-800 mt-1 flex items-center gap-2">
                        @if ($product->stock > 10)
                            <span class="text-emerald-700 font-bold">{{ $product->stock }}</span> en stock
                        @elseif ($product->stock > 0)
                            <span class="text-amber-700 font-bold">{{ $product->stock }}</span> (pocas unidades)
                        @else
                            <span class="text-rose-600 font-bold">Agotado</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-2">Descripción</h3>
                <div class="prose prose-sm text-slate-600 bg-white p-4 rounded-lg border border-slate-100">
                    {{ $product->description ?: 'Sin descripción registrada.' }}
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 text-xs text-slate-400 grid grid-cols-1 sm:grid-cols-2 gap-2">
                <div>Fecha de Creación: <span class="text-slate-600 font-medium">{{ $product->created_at->format('d/m/Y H:i') }}</span></div>
                <div>Última Actualización: <span class="text-slate-600 font-medium">{{ $product->updated_at->format('d/m/Y H:i') }}</span></div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 sm:px-8 py-4 border-t border-slate-200 flex items-center justify-between">
            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-rose-600 hover:text-rose-800 text-sm font-medium transition">
                    Eliminar Producto
                </button>
            </form>
            <div class="flex gap-3">
                <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
