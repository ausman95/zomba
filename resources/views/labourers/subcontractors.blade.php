@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-users"></i>Subcontractors
        </h4>
        <p>
            Manage Subcontractors accounts
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item active" aria-current="page">Subcontractors</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#material">
                <i class="fa fa-plus-circle"></i> New Subcontractor
            </button>
            <a href="{{route('transactions.create')}}"
               class="btn btn-primary btn-md rounded-0">
                <i class="fa fa-plus-circle"></i> New Payment
            </a>
            <div class="modal " id="material" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Adding Subcontractors</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('labourers.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="hidden" name="check" value="1">
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{old('name')}}"
                                           placeholder="Labourer's name"
                                    >
                                    @error('name')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-select select-relation @error('gender') is-invalid @enderror" style="width: 100%">{{old('gender')}}>
                                        <option value="">-- Select ---</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Customised">Customised</option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone_number"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           value="{{old('phone_number')}}"
                                           placeholder="Phone number"
                                    >
                                    @error('phone_number')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Professional/Labour</label>
                                    <select name="labour_id"
                                            class="form-select select-relation @error('labour_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($labours as $labour)
                                            <option value="{{$labour->id}}"
                                                {{old('labour_id')===$labour->id ? 'selected' : ''}}>{{$labour->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <select name="department_id"
                                            class="form-select select-relation @error('department_id') is-invalid @enderror" style="width: 100%">
                                        <option value="">-- Select ---</option>
                                        @foreach($departments as $department)
                                            <option value="{{$department->id}}"
                                                {{old('department_id')===$department->id ? 'selected' : ''}}>{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Labour Type</label>
                                    <select name="type" class="form-select select-relation @error('type') is-invalid @enderror" style="width: 100%">{{old('type')}}>
                                        <option value="2">Sub-Contractor
                                    </select>
                                    @error('type')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <hr style="height: .3em;" class="border-theme">
                                <div class="form-group">
                                    <button class="btn btn-md btn-primary rounded-0">
                                        <i class="fa fa-paper-plane"></i>Save
                                    </button>

                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="row">
                    <div class="col-sm-12 mb-2 col-md-12 col-lg-12">
                        <div class="card " style="min-height: 30em;">
                            <div class="card-body px-1">
                                @if($labourers->count() === 0)
                                    <i class="fa fa-info-circle"></i>There are no  Subcontractors!
                                @else
                                    <div style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover table-striped" id="data-table">
                                            <caption style=" caption-side: top; text-align: center">Subcontractors</caption>
                                            <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>NAME</th>
                                            <th>DEPARTMENT</th>
                                            <th>PHONE</th>
                                            <th>PROFESSIONAL</th>
                                            <th>BALANCE (MK)</th>
                                            <th>ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $c = 1;?>
                                        @foreach($labourers as $labourer)
                                            <tr>
                                                <td>{{$c++}}</td>
                                                <td>{{$labourer->name}}</td>
                                                <td>{{$labourer->department->name}}</td>
                                                <td>{{$labourer->phone_number}}</td>
                                                <td>{{$labourer->labour->name}}</td>
                                                <td>
                                                    @if($labourer->getBalance($labourer->id)<0)
                                                   ( {{number_format($labourer->getBalance($labourer->id))}})
                                                    @else
                                                        {{number_format($labourer->getBalance($labourer->id))}}
                                                    @endif
                                                </td>
                                                <td class="pt-1">
                                                    <a href="{{route('labourers.show',$labourer->id)}}"
                                                       class="btn btn-primary btn-md rounded-0">
                                                      <i class="fa fa-list-ol"></i>  Manage
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

@section('scripts')
    <script src="{{asset('vendor/simple-datatable/simple-datatable.js')}}"></script>
    <script>
        function confirmationWindow(title, message, primaryLabel, callback) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: primaryLabel
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            })
        }


        $(document).ready(function () {
            $(".delete-btn").on('click', function () {
                $url = $(this).attr('data-target-url');

                $("#delete-form").attr('action', $url);
                confirmationWindow("Confirm Deletion", "Are you sure you want to delete this position?", "Yes,Delete", function () {
                    $("#delete-form").submit();
                })
            });
        })
    </script>
@stop

