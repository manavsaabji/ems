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
                {{-- <div class="card-tools">
                    <a href="{{ route('task.create') }}" class="badge badge-primary"><i class="fa fa-plus"></i>&nbsp;
                        Add</a>
                </div> --}}
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if(auth()->user()->hasRole('admin'))
                            <th>User Name</th>
                            @endif
                            <th>Task 1</th>
                            <th>Task 2</th>
                            <th>Task 3</th>
                            <th>Task 4</th>
                            <th>Task 5</th>
                            <th>Task 6</th>
                            <th>Date</th>
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
                ajax: "{{ route('task.index') }}",
                order: [],
                sorting: true,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'index',
                        orderable: false,
                        searchable: false
                    },
                    @if(auth()->user()->hasRole('admin'))
                    {
                        data: 'user',
                        name: 'user',
                    },
                    @endif
                    {
                        data: 'task_1',
                        name: 'task_1',
                    },
                    {
                        data: 'task_2',
                        name: 'task_2',
                    },
                    {
                        data: 'task_3',
                        name: 'task_3',
                    },
                    {
                        data: 'task_4',
                        name: 'task_4',
                    },
                    {
                        data: 'task_5',
                        name: 'task_5',
                    },
                    {
                        data: 'task_6',
                        name: 'task_6',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                ],

            });
        });
    </script>
@endsection
