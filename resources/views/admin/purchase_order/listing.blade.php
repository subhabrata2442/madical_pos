@extends('layouts.admin')
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert />
                <div class="table-responsive dataTable-design">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                            
                            <th>Invoice No</th>
                            <th>Purchase date</th>
                            <th>Total Qty</th>
                            <th>Total Cost</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {

            var table = $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 350,
                ajax: "{{ route('admin.purchase.inward_list') }}",
                columns: [
                    {
                        data: 'invoice_no',
                        name: 'invoice_no'
                    },
                    {
                        data: 'purchase_date',
                        name: 'purchase_date'
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty'
                    },
                    {
                        data: 'sub_total',
                        name: 'sub_total',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });

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
    </script>
@endsection
