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
                                <li class="breadcrumb-item active">Leave Create</li>
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
                <h3 class="card-title">Create Leave</h3>
            </div>
            {!! Form::model($object, ['url' => $url, 'method' => $method]) !!}
            <div class="card-body">
                <input type="hidden" name="id" value={{ $object->id }}>
                <div class="form-group">
                    {!! Form::label('start_date', 'Start Date') !!}
                    {!! Form::date('start_date', null, ['class' => 'form-control', 'required', 'id' => 'start_date']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('end_date', 'End Date') !!}
                    {!! Form::date('end_date', null, ['class' => 'form-control', 'required', 'id' => 'end_date']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('reason', 'Reason') !!}
                    {!! Form::text('reason', null, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('leave_type', 'Leave Type') !!}
                    {!! Form::select(
                        'leave_type',
                        $leaveType,
                        null,
                        ['class' => 'form-control', 'required' => 'required','placeholder'=>'Select any one'],
                    ) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('leave_duration', 'Leave Duration') !!}
                    {!! Form::select(
                        'leave_duration',
                        $leaveDuration,
                        null,
                        ['class' => 'form-control','id'=>'leave_duration', 'required' => 'required', 'placeholder'=>'Select any one'],
                    ) !!}
                </div>

            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('footerScripts')
    <script>
        $(document).ready(function() {
            // Get current date in YYYY-MM-DD format
            var today = new Date().toISOString().split('T')[0];
            console.log(today);
            $('#start_date').attr('min', today); // Set minimum date for start_date to today's date

            // Validate end_date based on start_date
            $('#start_date, #end_date, #leave_duration').on('change', function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                // Ensure end_date is not earlier than start_date
                if (startDate && endDate) {
                    if (endDate < startDate) {
                        alert('End date should be equal to or after the start date.');
                        $('#end_date').val(''); // Clear the invalid end date
                    }
                    if (endDate > startDate) {
                        $('#leave_duration').val('full day');
                    }
                }
            });
        });
    </script>
@endsection
