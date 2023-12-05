<table id="" class="table table-bordered text-nowrap">
    <thead>
      <tr>
        <th scope="col">Store</th>
        <th scope="col">Barcode</th>
        <th scope="col">Brand</th>
        <th scope="col">Dosage Form</th>
        <th scope="col">Company</th>
        {{-- <th scope="col">Drugstore name</th> --}}
        <th scope="col">Total Stock</th>
        {{-- <th scope="col">Net Price</th>
        <th scope="col">Price</th>
        <th scope="col">US/IQ rate</th>
        <th scope="col">Sell Price</th> --}}
      </tr>
    </thead>
    <tbody>

    @forelse ($stockProducts as $Stock_product)
    @php
    $product_mrp=isset($Stock_product->stockProduct->product_mrp)?number_format($Stock_product->stockProduct->product_mrp,2):'-';
    $c_qty=isset($Stock_product->stockProduct->c_qty)?$Stock_product->stockProduct->c_qty:'-';
    $w_qty=isset($Stock_product->stockProduct->w_qty)?$Stock_product->stockProduct->w_qty:'-';
    @endphp
    <tr>
      <td>{{@$Stock_product->user->name}}</td>
      <td>{{@$Stock_product->product_barcode}}</td>
      <td>{{@$Stock_product->product->brand}}</td>
      <td>{{@$Stock_product->product->dosage_name}}</td>
      <td>{{@$Stock_product->product->company_name}}</td>
      {{-- <td>{{@$Stock_product->product->drugstore_name}}</td> --}}
      <td>{{@$Stock_product->t_qty}}</td>
      {{-- <td>{{@$Stock_product->product->net_price}}</td>
      <td>{{@$Stock_product->product->product_mrp}}</td>
      <td>{{@$Stock_product->product->product_mrp}}</td>
      <td>{{@$Stock_product->product->selling_price}}</td> --}}
    </tr>
    @empty
    <tr >
      <td colspan="10"> No data found </td>
    </tr>
    @endforelse
      </tbody>

  </table>
