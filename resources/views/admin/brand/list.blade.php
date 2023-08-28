@extends('layouts.admin')
@section('admin-content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <x-alert />
      <div class="table-responsive dataTable-design">
        <table id="brand_table" class="table table-bordered">
          <thead>
            <th>Brand Name</th> 
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

            var table = $('#brand_table').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 350,
                ajax: "{{ route('admin.brand.list') }}",
                columns: [{
                        data: 'name',
                        name: 'name',
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

        $(document).on('click', '#delete_brand', function() {
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
        });
    </script> 
@endsection 