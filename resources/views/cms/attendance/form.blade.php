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
                                <li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance List</a></li>
                                <li class="breadcrumb-item active">Attendance Mannual</li>
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
                <h3 class="card-title">Mannual Attendance</h3>
            </div>
            {!! Form::open(['url' =>  route('submitManuallyAttendance'), 'method' => 'POST']) !!}
            <div class="card-body">
                <div class="form-group">
                    {!! Form::label('user', 'User') !!}
                    {!! Form::select('user', $users, null, ['class' => 'form-control', 'required', 'placeholder' =>'Select User', 'id'=>'user']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('date', 'Select Date') !!}
                    {!! Form::date('date', null, ['class' => 'form-control', 'id'=>'date', 'required', 'max' => date('Y-m-d')]) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('punch_in', 'Punch In') !!}
                    {!! Form::time('punch_in', null, ['class' => 'form-control', 'id'=>'punch_in','required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('punch_out', 'Punch Out') !!}
                    {!! Form::time('punch_out', null, ['class' => 'form-control', 'id'=>'punch_out','required']) !!}
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
        var userSelect = document.getElementById("user");
        var dateSelect = document.getElementById("date");
        var punchIn = document.getElementById("punch_in");
        var punchOut = document.getElementById("punch_out");
        function onUserAndDateChange() {
            var selectedUser = userSelect.value;
            var selectedDate = dateSelect.value;
            if(selectedUser != '' && selectedDate != ''){
                // console.log(selectedUser, selectedDate);
                var requestData = {
                    user: selectedUser,
                    date: selectedDate
                };
                $.ajax({
                    type: "GET",
                    url: "{{ route('getManuallyAttendance') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: requestData,
                    dataType: "json",
                    success: function(data){
                        // alert("Data Saved: " + JSON.stringify(data.response));
                        if(data.response != ''){
                            punchIn.value = data.response.arrival_time;
                            punchOut.value = data.response.end_time;
                        }else{
                            punchIn.value = '';
                            punchOut.value = '';
                        }
                    }
                });
            }
        }


        function checkValuesPunchIn(){
            var punchInValue = punchIn.value;
            var punchOutValue = punchOut.value;
            if(punchOutValue != ''){
                if(punchInValue > punchOutValue){
                    punchIn.value = '';
                    alert('select correct time');
                }
            }
        }
        function checkValuesPunchOut(){
            var punchInValue = punchIn.value;
            var punchOutValue = punchOut.value;
            if(punchInValue != ''){
                if(punchInValue > punchOutValue){
                    punchOut.value = '';
                    alert('select correct time');
                }
            }
        }
        userSelect.addEventListener("change", onUserAndDateChange);
        dateSelect.addEventListener("change", onUserAndDateChange);
        punchIn.addEventListener("change", checkValuesPunchIn);
        punchOut.addEventListener("change", checkValuesPunchOut);
    </script>
@endsection
