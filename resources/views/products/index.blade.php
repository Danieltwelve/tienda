@extends('layouts.app')

@section('title', 'Lista de Productos')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Productos</h1>
            <p class="mt-1 text-sm text-slate-500">Gestione el inventario, precios y estado de sus artículos.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Crear Producto
            </a>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 md:col-span-7">
                <label for="search" class="sr-only">Buscar</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ $search }}" placeholder="Buscar por nombre o descripción..." class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="status" class="sr-only">Estado</label>
                <select name="status" id="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="sm:col-span-3 md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium py-2 px-3 rounded-lg transition">
                    Filtrar
                </button>
                @if ($search || $status)
                    <a href="{{ route('products.index') }}" class="flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium py-2 px-3 rounded-lg transition" title="Limpiar filtros">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Productos -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 font-semibold">
                    <tr>
                        <th scope="col" class="px-6 py-3.5">ID</th>
                        <th scope="col" class="px-6 py-3.5">Nombre</th>
                        <th scope="col" class="px-6 py-3.5">Precio</th>
                        <th scope="col" class="px-6 py-3.5">Stock</th>
                        <th scope="col" class="px-6 py-3.5">Estado</th>
                        <th scope="col" class="px-6 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    @forelse ($products as $product)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-mono text-xs text-slate-400">#{{ $product->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $product->name }}</div>
                                @if ($product->description)
                                    <div class="text-xs text-slate-500 truncate max-w-xs">{{ $product->description }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-900">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($product->stock > 10)
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">{{ $product->stock }} unidades</span>
                                @elseif ($product->stock > 0)
                                    <span class="inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20">Bajo ({{ $product->stock }})</span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-rose-50 px-2 py-1 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20">Agotado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($product->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('products.show', $product) }}" class="text-slate-600 hover:text-indigo-600 font-medium text-xs transition">Ver</a>
                                <span class="text-slate-300">|</span>
                                <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-xs transition">Editar</a>
                                <span class="text-slate-300">|</span>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-medium text-xs transition">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="mt-2 text-sm font-semibold text-slate-900">No hay productos registrados</p>
                                <p class="mt-1 text-sm text-slate-500">Comience creando un nuevo producto en el sistema.</p>
                                <div class="mt-6">
                                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                                        Crear Producto
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
