@extends('layouts.admin')

@section('title', 'Fichier Clients')
@section('subtitle', 'Consultez l\'historique d\'achats, la fidélité et les crédits.')

@section('content')
<div class="bg-white rounded-[18px] p-6 border border-slate-200 shadow-sm">
    <h3 class="text-base font-bold text-slate-900 mb-4">Liste des clients</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nom Client</th>
                    <th class="px-6 py-4">Téléphone</th>
                    <th class="px-6 py-4">Historique d'Achat</th>
                    <th class="px-6 py-4">Points Fidélité</th>
                    <th class="px-6 py-4">Solde Crédit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 text-xs font-medium">
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-900 font-bold">Rado Rabe</td>
                    <td class="px-6 py-4 text-slate-500">+261 34 11 222 33</td>
                    <td class="px-6 py-4 text-slate-900">14 Achats (320 000 Ar total)</td>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">320 pts</span>
                    </td>
                    <td class="px-6 py-4 text-slate-900">0 Ar</td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-900 font-bold">Sitraka Andria</td>
                    <td class="px-6 py-4 text-slate-500">+261 32 44 555 66</td>
                    <td class="px-6 py-4 text-slate-900">5 Achats (85 000 Ar total)</td>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">85 pts</span>
                    </td>
                    <td class="px-6 py-4 text-amber-600 font-bold bg-amber-50">15 000 Ar <span class="text-[9px] text-slate-500 font-medium block">Crédit dû</span></td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-900 font-bold">Rasoa Nirina</td>
                    <td class="px-6 py-4 text-slate-500">+261 33 77 888 99</td>
                    <td class="px-6 py-4 text-slate-900">28 Achats (540 000 Ar total)</td>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">540 pts</span>
                    </td>
                    <td class="px-6 py-4 text-slate-900">0 Ar</td>
                </tr>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-900 font-bold">Jean Rakoto</td>
                    <td class="px-6 py-4 text-slate-500">+261 34 55 666 77</td>
                    <td class="px-6 py-4 text-slate-900">3 Achats (45 000 Ar total)</td>
                    <td class="px-6 py-4">
                        <span class="bg-purple-100 text-purple-700 font-bold px-2.5 py-1 rounded-full text-[10px] uppercase">45 pts</span>
                    </td>
                    <td class="px-6 py-4 text-amber-600 font-bold bg-amber-50">8 500 Ar <span class="text-[9px] text-slate-500 font-medium block">Crédit dû</span></td>
                </tr>
            </tbody>
        </table>
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