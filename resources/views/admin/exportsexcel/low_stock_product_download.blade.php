<table>
    <thead>
        <tr>
            <th>Sl NO.</th>
            <th>Brand</th>
            {{-- <th>Product Name</th> --}}
            <th>In Stock</th>

            <th>Product Barcode</th>
            <th>Dosage</th>
            <th>Selling By</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($low_stock as $key=>$purchase)
            @if($purchase->t_qty <= $purchase->product->alert_product_qty)
                <tr>
                    <td>{{($key+1)}}</td>
                    <td>{{$purchase->product->brand}}</td>
                    {{-- <td>{{$purchase->product->product_name}}</td> --}}
                    <td>{{$purchase->t_qty}}</td>
                    <td>{{$purchase->product->product_barcode}}</td>
                    <td>{{$purchase->product->dosage_name}}</td>
                    <td>{{$purchase->product->selling_by_name}}</td>
                </tr>
            @endif
        @empty
            <tr ><td colspan="6"> No data found </td></tr>
        @endforelse

    </tbody>
</table>
