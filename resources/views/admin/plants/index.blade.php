@extends('admin.layout')

@section('title', 'Plant Catalog & Inventory — Plantify Official')

@section('admin_content')

    <div class="bg-paper border border-hairline rounded-2xl p-6 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-hairline">
            <div>
                <h2 class="font-display font-bold text-lg text-forest">Botanical Inventory &amp; Catalog</h2>
                <p class="text-xs text-forest-3 mt-0.5">Manage live plant inventory, stock reservations, and specimen status</p>
            </div>
            <a href="{{ route('shop') }}" target="_blank" class="btn btn-ghost !text-xs !py-1.5 !px-3 font-mono">
                View Live Catalog &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-hairline text-forest-4 font-mono uppercase text-[10px]">
                        <th class="py-2.5 px-3">Specimen</th>
                        <th class="py-2.5 px-3">Category</th>
                        <th class="py-2.5 px-3">Price</th>
                        <th class="py-2.5 px-3">Stock Level</th>
                        <th class="py-2.5 px-3">Care Level</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($plants as $plant)
                        <tr class="hover:bg-glass/50 transition-colors">
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-glass overflow-hidden flex-shrink-0 border border-hairline">
                                        <img src="{{ $plant->primary_image_url }}" alt="{{ $plant->name }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-forest">
                                            <a href="{{ route('product.show', ['slug' => $plant->slug]) }}" target="_blank" class="hover:text-ochre">
                                                {{ $plant->name }}
                                            </a>
                                        </p>
                                        <p class="text-[11px] text-forest-4 italic font-mono">{{ $plant->botanical_name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 text-forest-3">
                                {{ $plant->category->name ?? 'Houseplant' }}
                            </td>
                            <td class="py-3 px-3 font-mono font-bold text-forest">
                                ${{ number_format($plant->price, 2) }}
                            </td>
                            <td class="py-3 px-3">
                                @if($plant->stock <= 5 && $plant->stock > 0)
                                    <span class="badge badge-low text-[10px]">{{ $plant->stock }} remaining (Low)</span>
                                @elseif($plant->stock <= 0)
                                    <span class="badge badge-sale text-[10px]">Out of Stock (0)</span>
                                @else
                                    <span class="badge badge-stock text-[10px]">{{ $plant->stock }} available</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 capitalize text-forest-3">
                                {{ $plant->difficulty_level }}
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge {{ $plant->is_active ? 'badge-stock' : 'badge-sale' }} text-[10px]">
                                    {{ $plant->is_active ? 'Active' : 'Deactivated' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-right">
                                @if($plant->is_active)
                                    <form method="POST" action="{{ route('admin.plants.deactivate', ['id' => $plant->id]) }}" onsubmit="return confirm('Deactivate {{ $plant->name }}? Existing orders will preserve history.');">
                                        @csrf
                                        <button type="submit" class="btn btn-ghost !text-xs !py-1 !px-2.5 text-sale hover:bg-sale-wash border-sale/30">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-forest-4 italic font-mono">Archived</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-forest-3 font-mono text-xs">
                                No plant records found in the nursery database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-hairline">
            {{ $plants->links() }}
        </div>

    </div>

@endsection
