<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="overflow-hidden overflow-x-auto mb-4 min-w-full align-middle sm:rounded-md">
                        <table class="min-w-full mt-1 border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    @foreach ($tableItems as $item)
                                        <th class="px-6 py-3 text-left bg-gray-50">
                                            <span
                                                class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">{{ $item }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @forelse($tableData as $dataItem)
                                    <tr class="bg-white">
                                        <td class="px-6">
                                            <div class="inline-block relative mr-2 w-10 align-middle transition duration-200 ease-in select-none">
                                                <input wire:model="active.{{ $dataItem['id'] }}"  wire:click="toggleShowPriceV2({{ $dataItem['id'] }})"
                                                type="checkbox" name="toggle" id="{{ $loop->index.$dataItem['id'] }}"
                                                class="block absolute w-6 h-6 bg-white rounded-full border-4 appearance-none cursor-pointer focus:outline-none toggle-checkbox" />
                                                <label for="{{ $loop->index.$dataItem['id'] }}" class="block overflow-hidden h-6 bg-gray-300 rounded-full cursor-pointer toggle-label"></label>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if (!empty($dataItem['thumb_url']))
                                                <a style="cursor: pointer;" wire:click.prevent="export('pdf', '{{$dataItem['id']}}')">
                                                    @livewire('image-component', ['dataItem' => $dataItem], key($dataItem['id']))
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $dataItem['title'] }}
                                        </td>
                                        <td class="px-6 bg-secondary p-1 bg-slate-200" style="max-width: 220px!important">
                                            @if (array_key_exists($dataItem['id'], $active) && $active[$dataItem['id']])
                                                @forelse ($activePrices[$dataItem['id']]['prices'] as $key => $itemPrice)
                                                    {{ $key }} : € {{ $itemPrice }}
                                                    <br />
                                                @empty
                                                    Something went wrong, please try again
                                                @endforelse
                                            @else
                                                € {{ $dataItem['price'] }}
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white">
                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            No Data Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {!! $tableData->links() !!}
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
