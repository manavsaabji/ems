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
                                <li class="breadcrumb-item active"> User List</li>
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
                <h3 class="card-title">Users list</h3>
                <div class="card-tools">
                    <a href="{{ route('user.create') }}" class="badge badge-primary"><i class="fa fa-plus"></i>&nbsp; Add</a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}"><i class="fa fa-edit"></i><a>
                                            <form action="{{ route('user.destroy', ['user' => $user->id]) }}"
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
    <div class="row"></div>
@endsection

@section('footerScripts')
    <script>
        $(document).ready(function(){
            $('#table').DataTable({
                    "responsive": true,
                    "processing": true,
                    "serverSide": true,
                    ajax:"{{ route('user.index') }}",
                    order:[],
                    sorting:true,
                    columns:[
                        {
                        data: 'DT_RowIndex',
                        name: 'Index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
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
