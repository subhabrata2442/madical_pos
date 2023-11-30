<table>
    <thead>
    <tr>
        <th>Sl NO.</th>
        {{-- <th>Product Name</th> --}}
        <th>Brand Name</th>
        <th>In Stock</th>

        <th>Product Barcode</th>
        <th>Dosage</th>
        <th>Selling By</th>
    </tr>
    </thead>
    <tbody>
        @forelse ($result as $key=>$purchase)
        <tr>

            <td>{{($key+1)}}</td>
            <td>{{$purchase['brand']}}</td>
            {{-- <td>{{$purchase['product_name']}}</td> --}}
            <td>{{$purchase['t_qty']}}</td>

            <td>{{$purchase['product_barcode']}}</td>
            <td>{{$purchase['dosage_name']}}</td>
            <td>{{$purchase['selling_by_name']}}</td>
        </tr>
        @empty
            <tr ><td colspan="7"> No data found </td></tr>
        @endforelse

    </tbody>
</table>
