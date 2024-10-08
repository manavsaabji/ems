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
                                <li class="breadcrumb-item active"> Activity Logs</li>
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
                <h3 class="card-title">Activity Logs List</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Index</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Action By</th>
                            <th>Message</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
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
                    ajax:"{{ route('activityLogs') }}",
                    order:[[5,"desc"]],
                    sorting:true,
                    columns:[
                        {
                        data: 'DT_RowIndex',
                        name: 'Index',
                    },
                    {
                        data: 'Module',
                        name: 'Module'
                    },
                    {
                        data: 'Action',
                        name: 'Action'
                    },
                    {
                        data:'Responsible',
                        name:'Responsible'
                    },
                    {
                        data:'Message',
                        name:'Message'
                    },
                    {
                        data:'Time',
                        name:'Time'
                    },
                    ],

            });
        });
    </script>
@endsection
