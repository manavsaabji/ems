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
                                <li class="breadcrumb-item active"> Department List</li>
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
                <h3 class="card-title">Task list</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Phone no</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection


@section('footerScripts')
    <script>
        $(document).ready(function() {
            $('#table').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                ajax: "{{ route('test') }}",
                order: [],
                sorting: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name:'name',
                    },
                    {
                        data: 'email',
                        name:'email',
                    },
                    {
                        data: 'department',
                        name:'department',
                    },
                    {
                        data: 'role',
                        name:'role',
                    },
                    {
                        data: 'phone_no',
                        name:'phone_no',
                    },

                ],

            });
        });
    </script>
@endsection
