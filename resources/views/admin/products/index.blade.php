@extends('layouts.admin')

@section('title', 'Gestion des Produits')
@section('subtitle', 'Configurez vos produits, catégories, prix d\'achat et vente.')

@section('content')
<div x-data="{
    selectedCategory: 'all',
    searchQuery: '',
    showDeleteModal: false,
    productToDelete: null
}">
    <!-- Action Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-[18px] border border-slate-200 shadow-sm">
        <div class="flex-1 w-full sm:max-w-md relative">
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Rechercher une référence, désignation..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition-all">
            <i data-lucide="search" class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500"></i>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select x-model="selectedCategory" class="px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-green-600">
                <option value="all">Toutes les catégories</option>
                <option value="alimentation">Alimentation</option>
                <option value="boissons">Boissons</option>
                <option value="hygiene">Hygiène</option>
                <option value="divers">Divers</option>
            </select>
            <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm shadow-green-600/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Ajouter</span>
            </a>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-xs font-medium flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded-[18px] border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Référence</th>
                        <th class="px-6 py-4">Nom</th>
                        <th class="px-6 py-4">Catégorie</th>
                        <th class="px-6 py-4">Prix Achat</th>
                        <th class="px-6 py-4">Prix Vente</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Statut</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($products as $product)
                        <tr x-data='{"product": @json(["category" => $product->category, "name" => $product->name, "reference" => $product->reference]) }'
                            x-show="(selectedCategory === 'all' || product.category === selectedCategory) && (searchQuery === '' || product.name.toLowerCase().includes(searchQuery.toLowerCase()) || product.reference.toLowerCase().includes(searchQuery.toLowerCase()))"
                            class="hover:bg-slate-50/50 transition-all">
                            <td class="px-6 py-4">
                                <img class="w-10 h-10 rounded-lg object-cover bg-slate-100 border border-slate-100" 
                                     src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}"
                                     onerror="this.src='{{ asset('images/default-product.png') }}'">
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-900">{{ $product->reference }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <span class="text-xs font-bold text-slate-900 block">{{ $product->name }}</span>
                                    @if($product->barcode)
                                        <span class="text-[9px] text-slate-500 font-semibold block">Code: {{ $product->barcode }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                {{ ucfirst($product->category) }}
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-900">
                                {{ number_format($product->purchase_price, 0, ',', ' ') }} Ar
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-green-600">
                                {{ number_format($product->selling_price, 0, ',', ' ') }} Ar
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $product->is_out_of_stock ? 'bg-red-100 text-red-600' : 
                                       ($product->is_low_stock ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $product->status === 'active' ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="p-1.5 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-slate-100 transition-all"
                                       title="Voir">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" 
                                       class="p-1.5 text-slate-500 hover:text-green-600 rounded-lg hover:bg-slate-100 transition-all"
                                       title="Modifier">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <button @click="productToDelete = {{ $product->id }}; showDeleteModal = true" 
                                            class="p-1.5 text-slate-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-all"
                                            title="Supprimer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-500">
                                    <i data-lucide="package" class="w-12 h-12 stroke-[1.5] text-slate-300 mb-2"></i>
                                    <span class="text-xs font-semibold text-slate-900">Aucun produit trouvé</span>
                                    <span class="text-[10px] mt-1">Commencez par ajouter un nouveau produit.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer Pagination -->
        @if($products->hasPages())
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                <span class="text-[11px] font-medium text-slate-500">
                    Affichage de {{ $products->firstItem() }} à {{ $products->lastItem() }} sur {{ $products->total() }} produits
                </span>
                <div class="flex gap-1">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4 bg-slate-900/60" 
         style="display: none;">
        <div class="bg-white rounded-[18px] max-w-sm w-full p-6 space-y-6 shadow-xl border border-slate-200" @click.away="showDeleteModal = false">
            <div class="flex items-center justify-center">
                <div class="p-3 bg-red-100 text-red-600 rounded-full w-14 h-14 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-8 h-8 stroke-[2.5]"></i>
                </div>
            </div>
            
            <div class="space-y-1 text-center">
                <h3 class="text-base font-bold text-slate-900">Confirmer la suppression</h3>
                <p class="text-[11px] text-slate-500 font-medium">Voulez-vous vraiment supprimer ce produit ?</p>
                <p class="text-[10px] text-red-600 font-medium">Cette action est irréversible.</p>
            </div>
            
            <div class="flex gap-3">
                <button @click="showDeleteModal = false" 
                        class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-900 rounded-xl transition-all">
                    Annuler
                </button>
                <form :action="'/products/' + productToDelete" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    document.addEventListener('alpine:initialized', function() {
        lucide.createIcons();
    });
</script>
@endpush