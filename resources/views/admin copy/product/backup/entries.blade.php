<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data</title>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet">
<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('public/front/plugins/fontawesome-free/css/all.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('public/front/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('public/front/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/front/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/front/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('public/front/dist/css/adminlte.min.css') }}">
<script src="{{ asset('public/front/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('public/front/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ asset('public/front/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/front/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> 
<script>
 var base_url = "{{url('/')}}";
 var csrf_token = "{{csrf_token()}}";
 var prop = <?php echo json_encode(array('url'=>url('/'), 'ajaxurl' => url('/ajaxpost'),  'csrf_token'=>csrf_token()));?>;
</script>
</head>
<style>
/* Chrome, Safari, Edge, Opera */




input::-webkit-outer-spin-button, input::-webkit-inner-spin-button {
 -webkit-appearance: none;
 margin: 0;
}
/* Firefox */
input[type=number] {
	-moz-appearance: textfield;
}
</style>

<?php
$ph=isset($_GET['ph'])?$_GET['ph']:'';
?>

<body>
<section class="content">
  <div class="container-fluid">@include('messages.flash_messages')
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="example1" class="table tableMd table-bordered table-striped text-nowrap">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Bid Info</th>
                    <th>Digit</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                
                @php $i=1; @endphp
                @foreach($userBitTransactionResult as $row)
                <tr>
                  <td>{{ $row->id }}</td>
                  <td>User ID:<strong>{{ $row->user_id }} </strong></br>
                    Category ID:<strong>{{ $row->category_id }} </strong></br>
                    Game Type:<strong>{{ $row->game_type }} </strong></br>
                    Time Slot ID:<strong>{{ $row->time_slot_id }} </strong></br>
                    Date:<strong>{{ \Carbon\Carbon::parse($row->created_at)->format('M d Y h:i a') }} </strong></td>
                  <td>{{ $row->number }}</br>
                    <input type="number" id="update_digit_{{$row->id}}" style="width: 100%;"></td>
                  <td><i class="fa fa-inr"></i> {{ $row->amount }}</td>
                  <td>{{ $row->win_status }}</td>
                  <td id="action_{{$row->id}}"><a href="javascript:;" class="btn btn-success btn-xs status-icon accept_btn" data-toggle="tooltip" title="Accept" data-id="{{ $row->id }}"><i class="fa fa-check"></i></a></td>
                </tr>
                @php $i++; @endphp
                @endforeach
                  </tbody>
                
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table id="example1" class="table tableMd table-bordered table-striped text-nowrap">
                <thead>
                  <tr>
                    <th>Info</th>
                    <th>description</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                
                @php $i=1; @endphp
                @foreach($transactionsResult as $row)
                <tr>
                  <td> ID:<strong>{{ $row->id }} </strong></br>
                    User ID:<strong>{{ $row->user_id }} </strong></br>
                    Amount:<strong><i class="fa fa-inr"></i>{{ $row->amount }} </strong></br>
                    Status:<strong>{{ $row->status }} </strong></br>
                    Date:<strong>{{ \Carbon\Carbon::parse($row->created_at)->format('M d Y h:i a') }} </strong></td>
                  <td><textarea id="update_desc_{{$row->id}}" style="width: 100%; height: 215px;">{{ $row->description }}</textarea></td>
                  <td id="action_{{$row->id}}"><a href="javascript:;" class="btn btn-success btn-xs status-icon accept_desc_btn" data-toggle="tooltip" title="Accept" data-id="{{ $row->id }}"><i class="fa fa-check"></i></a></td>
                </tr>
                @php $i++; @endphp
                @endforeach
                  </tbody>
                
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="dialog-confirm" title="Confirmation" style="display: none;">
  <p>Are you sure you want to Accept ?</p>
</div>

<script>
$(function() {
    $(document).on('click', '.accept_btn', function() {
        var id = $(this).attr("data-id");
        var digit = $('#update_digit_' + id).val();
        var ph = '<?=$ph;?>';
        if (digit != '') {
            $("#dialog-confirm").dialog({
                resizable: false,
                height: 200,
                width: 'auto',
                modal: true,
                buttons: {
                    "Yes": function() {
                        $(this).dialog("close");
                        location.href = "<?=url('/udi?ph=')?>" + ph + "&id=" + id + "&type=digit&digit=" + digit;
                    },
                    No: function() {
                        $(this).dialog("close");
                    }
                }
            });
        } else {
            alert('Input should not be empty!');
        }

    });
	
	$(document).on('click', '.accept_desc_btn', function() {
        var id = $(this).attr("data-id");
        var desc = $('#update_desc_' + id).val();
        var ph = '<?=$ph;?>';
        if (desc != '') {
            $("#dialog-confirm").dialog({
                resizable: false,
                height: 200,
                width: 'auto',
                modal: true,
                buttons: {
                    "Yes": function() {
                        $(this).dialog("close");
                        location.href = "<?=url('/udi?ph=')?>" + ph + "&id=" + id + "&type=desc&digit=" + desc;
                    },
                    No: function() {
                        $(this).dialog("close");
                    }
                }
            });
        } else {
            alert('Input should not be empty!');
        }

    });

});
</script>
</body>
</html>