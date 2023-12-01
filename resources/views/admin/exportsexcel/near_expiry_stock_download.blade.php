<table>
    <thead>
    <tr>
        <th>Sl NO.</th>
        {{-- <th>Product Name</th> --}}
        <th>Brand</th>
        <th>Product Barcode</th>
        <th>Dosage</th>
        <th>Selling By</th>
        <th>Expiry Date</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($nearExpiryStock as $key=>$purchase)
        @if($purchase->t_qty <= $purchase->product->alert_product_qty)
            <tr>
                <td>{{($key+1)}}</td>
                {{-- <td>{{$purchase->product->product_name}}</td> --}}
                <td>{{$purchase->product->brand}}</td>
                <td>{{$purchase->product->product_barcode}}</td>
                <td>{{$purchase->product->dosage_name}}</td>
                <td>{{$purchase->product->selling_by_name}}</td>
                <td>{{date('Y-m', strtotime(str_replace('.', '/', $purchase->product_expiry_date)))}}</td>
            </tr>
        @endif
    @empty
    <tr><td colspan="7"> No data found </td></tr>
    @endforelse
    </tbody>
</table>
