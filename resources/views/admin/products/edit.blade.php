@extends('layouts.admin')

@section('title', 'Modifier le Produit')
@section('subtitle', 'Modifiez les informations du produit')

@section('content')
<div x-data="{
    selectedCategory: '{{ old('category', $product->category) }}',
    showPreview: {{ $product->image ? 'true' : 'false' }},
    previewImage: '{{ $product->image_url }}'
}">
    <div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm">
        <!-- Messages d'erreur globaux -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-medium mb-6 flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                <div>
                    <strong class="font-bold">Veuillez corriger les erreurs suivantes :</strong>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Colonne gauche -->
                <div class="space-y-5">
                    <!-- Référence -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Référence <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="reference" 
                               value="{{ old('reference', $product->reference) }}" 
                               required
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('reference') border-red-500 @enderror">
                        @error('reference')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nom du produit -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Nom du produit <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $product->name) }}" 
                               required
                               placeholder="Ex: Eau Vive 1.5L, Riz rouge 5kg..."
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code-barres -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Code-barres (EAN/UPC)</label>
                        <input type="text" 
                               name="barcode" 
                               value="{{ old('barcode', $product->barcode) }}" 
                               placeholder="Ex: 9781234567890"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('barcode') border-red-500 @enderror">
                        @error('barcode')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Catégorie <span class="text-red-500">*</span></label>
                        <select name="category" 
                                x-model="selectedCategory"
                                required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-600 focus:outline-none @error('category') border-red-500 @enderror">
                            <option value="alimentation">Alimentation</option>
                            <option value="boissons">Boissons</option>
                            <option value="hygiene">Hygiène</option>
                            <option value="divers">Divers</option>
                        </select>
                        @error('category')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Statut <span class="text-red-500">*</span></label>
                        <select name="status" 
                                required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-green-600 focus:outline-none @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="space-y-5">
                    <!-- Prix d'achat -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Prix d'achat (Ar) <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="purchase_price" 
                               value="{{ old('purchase_price', $product->purchase_price) }}" 
                               required
                               placeholder="Ex: 1000"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('purchase_price') border-red-500 @enderror">
                        @error('purchase_price')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix de vente -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Prix de vente (Ar) <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="selling_price" 
                               value="{{ old('selling_price', $product->selling_price) }}" 
                               required
                               placeholder="Ex: 1500"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('selling_price') border-red-500 @enderror">
                        @error('selling_price')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Stock <span class="text-red-500">*</span></label>
                            <input type="number" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   required
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('stock') border-red-500 @enderror">
                            @error('stock')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Stock minimum <span class="text-red-500">*</span></label>
                            <input type="number" 
                                   name="minimum_stock" 
                                   value="{{ old('minimum_stock', $product->minimum_stock) }}" 
                                   required
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('minimum_stock') border-red-500 @enderror">
                            @error('minimum_stock')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Image du produit -->
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Image du produit</label>
                        
                        @if($product->image)
                            <div x-show="!showPreview || previewImage !== '{{ $product->image_url }}'" class="mb-3">
                                <p class="text-[10px] text-slate-500 font-medium mb-2">Image actuelle :</p>
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-32 h-32 object-cover rounded-lg border border-slate-200">
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-green-600 transition-all cursor-pointer relative"
                             @click="$refs.imageInput.click()">
                            <input type="file" 
                                   name="image" 
                                   accept="image/jpeg,image/jpg,image/png,image/webp"
                                   x-ref="imageInput"
                                   @change="
                                       const file = $event.target.files[0];
                                       if (file) {
                                           showPreview = true;
                                           previewImage = URL.createObjectURL(file);
                                       }
                                   "
                                   class="hidden">
                            
                            <div x-show="!showPreview" class="space-y-2">
                                <i data-lucide="upload-cloud" class="w-8 h-8 text-slate-400 mx-auto"></i>
                                <p class="text-[10px] text-slate-500 font-medium">Cliquez pour changer l'image</p>
                                <p class="text-[9px] text-slate-400">JPG, JPEG, PNG, WEBP (max 100 Mo)</p>
                            </div>

                            <div x-show="showPreview" class="space-y-2">
                                <img :src="previewImage" class="w-32 h-32 object-cover rounded-lg mx-auto border border-slate-200">
                                <p class="text-[10px] text-green-600 font-medium">Nouvelle image sélectionnée</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase block mb-1.5">Description</label>
                <textarea name="description" 
                          rows="3"
                          placeholder="Description détaillée du produit..."
                          class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-green-600 focus:outline-none @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('products.index') }}" 
                   class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-xs font-bold text-slate-900 rounded-xl transition-all">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Mettre à jour</span>
                </button>
            </div>
        </form>
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