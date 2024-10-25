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
                                <li class="breadcrumb-item active"> Leave History</li>
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
                <h3 class="card-title">Leave History</h3>

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Leave Duration</th>
                            <th>Leave Type</th>
                            <th>Reason</th>
                            <th>Status</th>
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
                ajax: "{{ route('leaveIndexAll') }}",
                order: [],
                sorting: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name:'user',
                    },
                    {
                        data: 'start_date',
                        name:'start_date',
                    },
                    {
                        data: 'end_date',
                        name:'end_date',
                    },
                    {
                        data: 'leave_duration',
                        name:'leave_duration',
                    },
                    {
                        data: 'leave_type',
                        name:'leave_type',
                    },
                    {
                        data: 'reason',
                        name:'reason',
                    },
                    {
                        data: 'status',
                        name:'status',
                    },

                ],

            });
        });
    </script>
@endsection
