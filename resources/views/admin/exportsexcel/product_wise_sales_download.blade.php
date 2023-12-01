<table id="" class="table table-bordered text-nowrap">
    <thead>
      <tr>
        <th scope="col">Invoice No</th>
        <th scope="col">Bill Date</th>
        <th scope="col">Customer</th>
        <th scope="col">Store</th>
        {{-- <th scope="col">Product Name</th> --}}
        <th scope="col">Brand</th>
        <th scope="col">Barcode</th>
        <th scope="col">Qty</th>
        <th scope="col">Mrp</th>
        <th scope="col">Total Cost</th>
      </tr>
     </thead>
    <tbody>
      @forelse ($queryProduct as $product)
      <tr>
          <th>{{@$product->sellInwardStock->invoice_no}}</th>
          <td>{{@date('d-m-Y', strtotime($product->sellInwardStock->sell_date))}}</td>
          <td>{{@$product->sellInwardStock->customer->customer_name}}</td>
          <td>{{@$product->sellInwardStock->storeUser->name}}</td>
          {{-- <td>{{$product->product_name;}}</td> --}}
          <td>{{@$product->brand_name}}</td>
          <td>{{@$product->barcode}}</td>
          <td>{{$product->product_qty}}</td>
          <td>{{number_format($product->product_mrp,2)}}</td>
          <td>{{number_format($product->total_cost,2)}}</td>
      </tr>
      @empty
          <tr ><td colspan="11"> No data found </td></tr>
      @endforelse

    </tbody>
  </table>
