@extends('layouts.admin')
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert />
                <form action="" method="get" id="filter">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                            <div class="form-group">
                                <input type="text" class="form-control" name="datefilter" id="reportrange" placeholder="Purhase date " autocomplete="off" value="{{request()->input('datefilter')}}">
                                <input type="hidden" name="start_date" id="start_date" value="{{request()->input('start_date')}}">
                                <input type="hidden" name="end_date" id="end_date" value="{{request()->input('end_date')}}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                            <button class="saveBtn-2" type="submit">Search <i class="fas fa-arrow-circle-right"></i></button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive dataTable-design">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                            <th>Store</th>
                            <th>Invoice No</th>
                            <th>Supplier</th>
                            <th>Purchase date</th>
                            <th>Total Qty</th>
                            <th>Total Cost</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($data['purchase_list'] as $row)
                                <tr>
                                    <td>{{$row->user->name}}</td>
                                    <td>{{$row->invoice_no}}</td>
                                    @if($row->supplier_id!=Null)
                                        <td>{{$row->supplier->company_name}}</td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>{{date('d-m-Y', strtotime($row->purchase_date))}}</td>
                                    <td>{{$row->total_qty}}</td>
                                    <td>{{number_format($row->sub_total,2)}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <div class="actionList " id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>

                                            </div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a class="dropdown-item" href="{{route('admin.purchase.inward_edit', [base64_encode($row->id)])}}">Edit</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript">
        // $(function() {

        //     var table = $('#user-table').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         searchDelay: 350,
        //         ajax: "{{ route('admin.purchase.inward_list') }}",
        //         columns: [
        //             {
        //                 data: 'store_name',
        //                 name: 'store_name'
        //             },
        //             {
        //                 data: 'invoice_no',
        //                 name: 'invoice_no'
        //             },
        //             {
        //                 data: 'company_name',
        //                 name: 'company_name'
        //             },
        //             {
        //                 data: 'purchase_date',
        //                 name: 'purchase_date'
        //             },
        //             {
        //                 data: 'total_qty',
        //                 name: 'total_qty'
        //             },
        //             {
        //                 data: 'sub_total',
        //                 name: 'sub_total',
        //             },
        //             {
        //                 data: 'action',
        //                 name: 'action',
        //                 orderable: false,
        //                 searchable: false
        //             },
        //         ]
        //     });

        // });

        $(document).on('click', '#delete_user', function() {
            var url = $(this).data('url');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location = url;
                }
            })
        })
        $(document).on('click', '#change_status', function() {
            var url = $(this).data('url');
            var status = $(this).data('status');
            var type = status == '1' ? 'unblock' : 'block';
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to ${type} this user?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location = url;
                }
            })
        })

$(function() {



$('#download_report').on("click",function(){
    var report_type = $('#report_type').val();
    var start_date = $('input[name=start_date]').val();
    var end_date = $('input[name=end_date]').val();
    if(report_type == ''){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select report type!',
        })
    }else if(start_date == '' && end_date== ''){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please select date!',
        })
    }else{
        console.log('sdfd');
        var url = "{{route('admin.report.sales.product.item_wise')}}";
        var href = url+'?start_date='+start_date+'&end_date='+end_date;

        window.open(href);
        //$(this).attr('href',url+'?start_date='+start_date+'&end_date='+end_date);
        //window.location = window.location.href;
    }


})
//Start date range picker
/* var start = moment().subtract(29, 'days');
var end = moment();
 */

/*  function cb(start, end) {
    //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#reportrange').val(start.format('D-MM-YYYY') + ' - ' + end.format('D-MM-YYYY'));
    $('#start_date').val(start.format('YYYY-MM-DD'));
    $('#end_date').val(end.format('YYYY-MM-DD'));
} */

$('#reportrange').daterangepicker({
    //startDate: start,
    //endDate: end,
    autoUpdateInput: false,
    ranges: {
       'Today': [moment(), moment()],
       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
       'This Month': [moment().startOf('month'), moment().endOf('month')],
       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    }
});

//cb(start, end);

$('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
    $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
  });
//End Date range picker
$('.searchDropBtn').on("click",function(){
    $(".toggleCard").slideToggle();
})





//reset form
$("#reset").click(function() {
    $('#filter').trigger("reset");
    window.location = window.location.href.split("?")[0];
});
});
    </script>
@endsection
