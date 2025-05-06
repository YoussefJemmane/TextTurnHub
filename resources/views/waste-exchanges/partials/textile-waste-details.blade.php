<div class="p-6 border-b border-gray-200">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Textile Waste Details</h2>
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500 w-1/3">Title</td>
                        <td class="py-2 font-medium">{{ $textileWaste->title }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Waste Type</td>
                        <td class="py-2 font-medium capitalize">{{ $textileWaste->waste_type }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Material Type</td>
                        <td class="py-2 font-medium">{{ $textileWaste->material_type }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-500">Description</td>
                        <td class="py-2">{{ $textileWaste->description ?? 'No description provided' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="w-full">
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500 w-1/3">Total Quantity</td>
                        <td class="py-2 font-medium">{{ number_format($textileWaste->quantity, 2) }} {{ $textileWaste->unit }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Condition</td>
                        <td class="py-2 font-medium">{{ $textileWaste->condition }}</td>
                    </tr>
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-500">Location</td>
                        <td class="py-2 font-medium">{{ $textileWaste->location }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-500">Price Per Unit</td>
                        <td class="py-2 font-medium">
                            @if($textileWaste->price_per_unit)
                                ${{ number_format($textileWaste->price_per_unit, 2) }}/{{ $textileWaste->unit }}
                            @else
                                Free
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
