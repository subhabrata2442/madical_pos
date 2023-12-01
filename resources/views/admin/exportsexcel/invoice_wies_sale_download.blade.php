<table id="" class="table table-bordered text-nowrap">
    <thead>
        <tr>
            <th scope="col">Invoice No</th>
            <th scope="col">Store Name</th>
            <th scope="col">Sell Date</th>
            <th scope="col">Total Qty</th>
            <th scope="col">Gross Amount</th>
            <th scope="col">Discount Amount</th>
            <th scope="col">Sub Total</th>
            <th scope="col">Pay Amount</th>
            <th scope="col">Payment Method</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sales as $sale)
        <tr>
            <th>{{$sale->invoice_no}}</th>
            <th>{{@$sale->storeUser->name}}</th>
            <td>{{date('d-m-Y', strtotime($sale->sell_date))}}</td>
            <th>{{$sale->total_qty}}</th>
            <th>{{number_format($sale->gross_amount,2)}}</th>
            <th>{{number_format($sale->discount_amount,2)}}</th>
            <th>{{number_format($sale->sub_total,2)}}</th>
            <th>{{number_format($sale->pay_amount,2)}}</th>
            <th>{{$sale->payment_method}}</th>
        </tr>
        @empty
            <tr ><td colspan="11"> No data found </td></tr>
        @endforelse

    </tbody>
</table>
