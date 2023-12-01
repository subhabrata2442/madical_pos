<table id="" class="table table-bordered text-nowrap">
    <thead>
        <tr>
            <th scope="col">Invoice No</th>
            <th scope="col">Store Name</th>
            <th scope="col">Inward date</th>
            <th scope="col">Purchase date</th>
            <th scope="col">Total Qty</th>
            <th scope="col">Total Cost</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($purchasess as $purchase)
        <tr>
            <td>{{$purchase->invoice_no}}</td>
            <th>{{@$purchase->user->name}}</th>
            <td>{{date('d-m-Y', strtotime($purchase->inward_date))}}</td>
            <td>{{date('d-m-Y', strtotime($purchase->purchase_date))}}</td>
            <td>{{$purchase->total_qty}}</td>
            <td>{{number_format($purchase->sub_total,2)}}</td>
        </tr>
        @empty
            <tr ><td colspan="5"> No data found </td></tr>
        @endforelse

    </tbody>
</table>
