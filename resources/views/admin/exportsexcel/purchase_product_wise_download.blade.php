<table id="" class="table table-bordered text-nowrap">
    <thead>
        <tr>
            <th scope="col">Invoice No</th>
            <th scope="col">Purchase Date</th>
            <th scope="col">Store</th>
            <th scope="col">Supplier</th>
            <th scope="col">Product Name</th>
            <th scope="col">Brand Name</th>
            <th scope="col">Product Barcode</th>
            <th scope="col">Dosage</th>
            <th scope="col">Selling By</th>
            <th scope="col">Company Name</th>
            <th scope="col">Qty</th>
            <th scope="col">Net Price</th>
            <th scope="col">Selling Price</th>
            <th scope="col">Profit Amount</th>
        </tr>

     </thead>
    <tbody>
      @forelse ($productss as $product)
      <tr>
          <td>{{@$product->purchaseInwardStock->invoice_no}}</td>
          <td>{{date('d-m-Y', strtotime($product->purchaseInwardStock->purchase_date))}}</td>
          <td>{{@$product->store->name}}</td>
          <td>{{$product->purchaseInwardStock->supplier_name}}</td>
          <td>{{$product->product_name}}</td>
          <td>{{$product->brand}}</td>
          <td>{{$product->product->product_barcode}}</td>
          <td>{{$product->dosage}}</td>
          <td>{{$product->selling_by}}</td>
          <td>{{$product->company}}</td>
          <td>{{$product->product_qty}}</td>
          <td>{{number_format($product->net_price,2)}}</td>
          <td>{{number_format($product->selling_price,2)}}</td>
          <td>{{number_format($product->profit_amount,2)}}</td>
      </tr>
      @empty
          <tr ><td colspan="13"> No data found </td></tr>
      @endforelse

    </tbody>
  </table>
