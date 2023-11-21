@extends('layouts.admin')
@section('admin-content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-alert />
                <div class="mb-12">
                    <form method="post" action="{{ route('admin.expense.add') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                              <label for="category_name" class="form-label">Category</label>
                              <input type="text" class="form-control admin-input" id="category_name" name="category_name"
                                                    value="{{ old('category_name') }}" required  autocomplete="off" placeholder="Category">
                              @error('category_name')
                              <div class="error admin-error">{{ $message }}</div>
                              @enderror 
                            </div>
                        </div>
                        <input type="hidden" name="id" id="id" value="">
                        <div class="col-12">
                            <button class="commonBtn-btnTag" type="submit">Submit </button>
                        </div>
                    </form>
                </div>
                <div class="table-responsive dataTable-design">
                    <table id="user-table" class="table table-bordered">
                        <thead>
                            
                            <th>Sl No.</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($data['category'] as $key=>$item)
                                <tr>
                                    <td>{{($key+1)}}</td>
                                    <td>{{$item->category_name}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <div class="actionList " id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <svg style="cursor: pointer;" xmlns="http://www.w3.org/2000/svg" width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-sliders dropdown-toggle" data-toggle="dropdown"
                                                    role="button" aria-expanded="false">
                                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a class="dropdown-item"
                                                    href="javascript:void(0)" onclick="editCategory('{{$item->id}}', '{{$item->category_name}}')">Edit</a>
                                                <a class="dropdown-item delete-item" href="javascript:;" id="delete_product"
                                                    data-url="{{route('admin.expense.category.delete', [base64_encode($item->id)]) }}">Delete</a>
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
    <script type="text/javascript">
        // $(function() {

        //     var table = $('#user-table').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         searchDelay: 350,
        //         ajax: "{{ route('admin.expense.category') }}",
        //         columns: [
        //             {
        //                 data: 'name',
        //                 name: 'name'
        //             },
        //             {
        //                 data: 'email',
        //                 name: 'email'
        //             },
        //             {
        //                 data: 'ph_no',
        //                 name: 'ph_no'
        //             },
        //             {
        //                 data: 'status',
        //                 name: 'status',
        //                 orderable: false,
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

        function editCategory(id, category_name){
            $("#category_name").val(category_name);
            $("#id").val(id); 
        }
    </script>
@endsection
