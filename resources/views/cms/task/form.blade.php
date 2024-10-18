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
                            <li class="breadcrumb-item"><a href="{{ route('task.index') }}">Task List</a></li>
                            <li class="breadcrumb-item active">Task Create</li>
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
            <h3 class="card-title">Create Task</h3>
        </div>
        {!! Form::model($object, ['url' => $url, 'method' => $method]) !!}
        <div class="card-body">
            <input type="hidden" name="id" value={{ $object->id }}>
            <div class="form-group">
                {!! Form::label('task_1', 'Task 1') !!}
                {!! Form::text('task_1', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('task_2', 'Task 2') !!}
                {!! Form::text('task_2', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('task_3', 'Task 3') !!}
                {!! Form::text('task_3', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('task_4', 'Task 4') !!}
                {!! Form::text('task_4', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('task_5', 'Task 5') !!}
                {!! Form::text('task_5', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('task_6', 'Task 6') !!}
                {!! Form::text('task_6', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
