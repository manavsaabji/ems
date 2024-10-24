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
                                <li class="breadcrumb-item"><a href="{{ route('leave.index') }}">Leave List</a></li>
                                <li class="breadcrumb-item active">Update Create</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Update Leave</h3>
            </div>
            {!! Form::model($object, ['url' => $url, 'method' => $method]) !!}
            <div class="card-body">
                <input type="hidden" name="id" value={{ $object->id }}>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{!! Form::label('user', 'User Name') !!}</th>
                            <th>{{ ucfirst($object->user->name) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{!! Form::label('start_date', 'Start Date') !!}</td>
                            <td>{{ ($object->start_date) }}</td>
                        </tr>
                        <tr>
                            <td>{!! Form::label('end_date', 'End Date') !!}</td>
                            <td>{{ $object->end_date }}</td> <!-- Fixed to use $object->end_date -->
                        </tr>
                        <tr>
                            <td>{!! Form::label('reason', 'Reason') !!}</td>
                            <td>{{ ucfirst($object->reason) }}</td>
                        </tr>
                        <tr>
                            <td>{!! Form::label('leave_type', 'Leave Type') !!}</td>
                            <td>{{ ucfirst($object->leave_type) }}</td>
                        </tr>
                        <tr>
                            <td>{!! Form::label('leave_duration', 'Leave Duration') !!}</td>
                            <td>{{ ucfirst($object->leave_duration) }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', $status, null, ['class' => 'form-control','id'=>'status', 'required' => 'required', 'placeholder'=>'Select any one'],) !!}
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

