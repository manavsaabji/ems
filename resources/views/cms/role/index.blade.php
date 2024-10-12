@extends('cms.layouts.master')

@section('content')
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="mb-2 row">
                        <div class="col-sm-6">
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active"> Role List</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card-primary card-outline card">
            <div class="card-header">
                <h3 class="card-title">Role list</h3>
                <div class="card-tools">
                    <a href="{{ route('role.create') }}" class="badge badge-primary"><i class="fa fa-plus"></i>&nbsp; Add</a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Assign Permission</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <a href="{{ route('role.edit', ['role' => $role->id]) }}"><i class="fa fa-edit"></i><a>
                                            <form action="{{ route('role.destroy', ['role' => $role->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" style="background-color: transparent;border:0px"><i
                                                        class="fa fa-trash text-red"></i></button>
                                            </form>
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection

@section('footerScripts')
    <script>
        $(document).ready(function(){
            $('#table').DataTable({
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
                    ajax:"{{ route('role.index') }}",
                    order:[],
                    sorting:true,
                    columns:[
                    {
                        data: 'DT_RowIndex',
                        name: 'index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'assign_permission',
                        name: 'assign_permission',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data:'action',
                        name:'action',
                        orderable: false,
                        searchable: false
                    },
                    ],

            });
        });
    </script>
@endsection
