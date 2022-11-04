@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('vendor/simple-datatable/simple-datatable.css')}}">
@stop

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fab fa-acquisitions-incorporated"></i> Requisitions
        </h4>
        <p>
            Create Requisition
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('requisitions.index')}}">Requisitions</a></li>
                @if(request()->user()->designation!='clerk')
                    <li class="breadcrumb-item"><a href="{{route('requisitions.determine')}}">Selection</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Create Requisition</li>
            </ol>
        </nav>
        <div class="mb-5">
            <button type="button" class="btn btn-primary rounded-0 btn-md" data-bs-toggle="modal" data-bs-target="#material">
                <i class="fa fa-plus-circle"></i> New Material
            </button>
            <div class="modal " id="material" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Adding Materials</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{route('materials.store')}}" method="POST" autocomplete="off">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{old('name')}}"
                                           placeholder="Material's name" >
                                    @error('name')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Units of Measurement</label>
                                    <input type="text" name="units"
                                           class="form-control @error('units') is-invalid @enderror"
                                           value="{{old('units')}}"
                                           placeholder="Units of Measurement" >
                                    @error('units')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                                <hr style="height: .3em;" class="border-theme">
                                <div class="form-group">
                                    <label>Specifications</label>
                                    <textarea name="specifications" rows="3" placeholder="Provide a short description of the materials"
                                              class="form-control @error('specifications') is-invalid @enderror">{{old('specifications')}}</textarea>
                                    @error('address')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>

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
            <hr>
            <p>
                Submit requisition for new materials.
            </p>
            <div class="col-sm-12 mb-2 md-4">
                <?php if($_GET['department']==='Projects'){
                ?>
                <p class="text-black-50">
                    Project
                </p>
                <h4>{{$project->getProjectName()}}</h4>
                <?php
                }else{
                ?>
                <p class="text-black-50">
                    Department / Site
                </p>
                <h4>{{$project->getDepartmentName()}}</h4>
                <?php
                }?>

            </div>
            <div class="mt-4 row">
                <div class="col-sm-12 col-md-4  mb-2">
                    <form action="{{route('requisitions.enlist')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Select Request Type</label>
                            <select name="type" class="form-select type select-relation" style="width: 100%">
                                <option value="">-- Select Material --</option>
                                <option value="1">Material</option>
                                <option value="2">Non Material</option>
                            </select>
                        </div>
                        <div class="material d-none">
                            <div class="form-group">
                                <label for="">Select Material</label>
                                <select name="material_id" class="form-select select-relation" style="width: 100%">
                                    <option value="">-- Select Material --</option>
                                    @foreach($materials as $material)
                                        <option value="{{$material->id}}">{{$material->name}}
                                            - {{$material->specifications}}</option>
                                    @endforeach
                                </select>
                                @error('material_id')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="">Quantity</label>
                                <input type="number" class="form-control" name="quantity" placeholder="Quantity">
                                @error('quantity')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="others d-none">
                            <div class="form-group">
                                <label for="">Enter Description</label>
                                <div class="form-group">
                                     <textarea name="description" rows="2"
                                               class="form-control @error('description') is-invalid @enderror">{{old('description')}}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Total Amount</label>
                                <input type="number" class="form-control" name="amount" placeholder="amount">
                                @error('amount')
                                <span class="invalid-feedback">
                               {{$message}}
                        </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reasons</label>
                            <textarea name="reason" rows="2"
                                      class="form-control @error('reason') is-invalid @enderror">{{old('reason')}}</textarea>
                            @error('reason')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Supplier (Enter there Details in Summary) </label>
                            <textarea name="personale" rows="2"
                                      class="form-control @error('personale') is-invalid @enderror">{{old('personale')}}</textarea>
                            @error('personale')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary rounded-0" type="submit">
                                <i class="fa fa-plus-circle"></i>  Add Item
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12 mb-2 col-md-8">
                    <div class="card bg-light">
                        <div class="card-header">
                            Requisition Items List
                        </div>
                        @if(count($requisition_list) === 0)
                            <div class="card-body p-5" style="min-height: 20em;">
                                <div class="text-center">
                                    List is empty
                                </div>
                            </div>
                        @else
                            <div class="ul list-group list-group-flush">
                                @foreach($requisition_list  as $list_item)
                                    <li class="list-group-item justify-content-between d-flex">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                {{$loop->iteration}}
                                            </div>
                                            <div>
                                                {{$list_item['material_name']}}
                                                <div class="text-black-50">
                                                   Reason :  {{$list_item['reason']}}

                                                </div>
                                                <div class="text-black-50">
                                                  Supplier :   {{@$list_item['personale']}}

                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="me-4">
                                                <span class="text-black-50">Amount (MK)</span>
                                                : <strong>{{number_format($list_item['amount'])}}</strong>
                                            </div>
                                            <div>
                                                <a href="{{route('requisitions.delist',$list_item['material_name'])}}"
                                                   title="Remove item"> <i
                                                        class="fa fa-minus-circle text-danger"></i></a>
                                            </div>

                                        </div>
                                    </li>
                                @endforeach
                            </div>
                        @endif
                        <div class="card-footer">
                            <?php if($_GET['department']==='Projects'){?>
                            <a href="{{route('requisitions.store')."?department={$_GET['department']}&project_id={$_GET['project_id']}"}}" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>  Send Requisition
                            </a>
                            <?php }else{?>
                            <a href="{{route('requisitions.store')."?department={$_GET['department']}&department_id={$_GET['department_id']}"}}" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i>  Send Requisition
                            </a>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.material').addClass('show').removeClass('d-none');
                    $('.others').addClass('d-none').removeClass('show');
                }
                if(status==='2'){
                    $('.material').addClass('d-none').removeClass('show');
                    $('.others').addClass('show').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
