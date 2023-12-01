<table id="" class="table table-bordered text-nowrap">
    <thead>
        <tr>
            <th scope="col">Sl NO.</th>
            <th scope="col">Store</th>
            {{-- <th scope="col">Product Name</th> --}}
            <th scope="col">In Stock</th>
            <th scope="col">Brand</th>
            <th scope="col">Product Barcode</th>
            <th scope="col">Dosage</th>
            <th scope="col">Selling By</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($zero_stock as $key=>$purchase)
            <tr>
                <td>{{($key+1)}}</td>
                <td>{{@$purchase->user->name}}</td>
                {{-- <td>{{$purchase->product->product_name}}</td> --}}
                <td>{{$purchase->t_qty}}</td>
                <td>{{$purchase->product->brand}}</td>
                <td>{{$purchase->product->product_barcode}}</td>
                <td>{{$purchase->product->dosage_name}}</td>
                <td>{{$purchase->product->selling_by_name}}</td>
            </tr>
        @empty
            <tr ><td colspan="7"> No data found </td></tr>
        @endforelse

    </tbody>
</table>
