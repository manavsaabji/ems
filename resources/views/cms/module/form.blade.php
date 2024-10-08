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
                            <li class="breadcrumb-item"><a href="{{ route('module.index') }}">Module List</a></li>
                            <li class="breadcrumb-item active"> Module Create</li>
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
            <h3 class="card-title">Create Module</h3>
        </div>
        {!! Form::model($object, ['url' => $url, 'method' => $method]) !!}
        <div class="card-body">
            <input type="hidden" name="id" value={{ $object->id }}>
            <div class="form-group">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
