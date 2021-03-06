@extends('layouts.backend.master')

@section('title', 'Leave-Application')

@push('css')


@endpush


@section('content')

    <div class="container-fluid">
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Total Applications
                            <span class="badge bg-blue">{{ $applications->count() }}</span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                @foreach($applications as $key=> $application)
                                    @php
                                        $user = \App\User::findOrFail($application->user_id);
                                        $leave_type = \App\LeaveType::findOrFail($application->leave_type_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $key +1 }}</td>
                                        <td>{{$application->emp_name}}</td>
                                        <td>{{$leave_type->leave_type}}</td>
                                        <td>{{$application->str_date->format('dM Y')}}</td>
                                        <td>{{$application->end_date->format('dM Y')}}</td>
                                        <td>
                                            @if($application->status == 1)
                                                <p class="bg-success text-center">Accepted</p>
                                            @elseif($application->status == 2)
                                                <p class="bg-danger text-center">Rejected</p>
                                            @else
                                                <p class="bg-warning text-center">Pending</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-info waves-effect" href="{{route('admin.leave.show', $application->id)}}">
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <button type="button" name="button" class="btn btn-success waves-effect" onclick="approveApplication({{ $application->id }})">
{{--                                                <i class="material-icons">done </i>--}}
                                                <i class="far fa-check-circle"></i>
                                            </button>

                                            <form  id="approve-application-{{$application->id}}" action="{{route('admin.application.update', $application->id)}}"
                                                   method="post" style="display:none;"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" value="true" name="status">
                                            </form>

                                            <button type="button" name="button" class="btn btn-danger waves-effect" onclick="rejectApplication({{ $application->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            <form  id="reject-application-{{$application->id}}" action="{{route('admin.application.reject', $application->id)}}"
                                                   method="post" style="display:none;"
                                            >
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" value="false" name="status">

                                            </form>

                                        </td>
                                    </tr>

                                    @endforeach

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>

@endsection

@push('js')


<script type="text/javascript">


    function rejectApplication(id) {

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You want to Reject the application!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Reject!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('reject-application-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                )
            }
        })

    }
    //  Approve application
    function approveApplication(id) {

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You want to Accept the application!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Accept!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('approve-application-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                )
            }
        })
    }
</script>
@endpush
