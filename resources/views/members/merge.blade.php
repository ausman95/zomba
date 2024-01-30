@extends('layouts.app')


@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Members
        </h4>
        <p>
            Manage Members
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item active" aria-current="page">Merge Members</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('member.edit-merge')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Delete this Member</label>
                            <select name="member_id_to_delete"
                                    class="form-select select-relation @error('member_id_to_delete') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('member_id_to_delete')===$member->id ? 'selected' : ''}}>
                                        {{$member->name.' '.$member->church->name}}</option>
                                @endforeach
                            </select>
                            @error('member_id_to_delete')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Keep this Member</label>
                            <select name="member_to_keep"
                                    class="form-select select-relation @error('member_to_keep') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($members as $member)
                                    <option value="{{$member->id}}"
                                        {{old('member_to_keep')===$member->id ? 'selected' : ''}}>
                                        {{$member->name.' '.$member->church->name}}</option>
                                @endforeach
                            </select>
                            @error('member_to_keep')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i>Merge
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function () {
            // $('.btn-success').click(function () {
            //     let check = $('.form-control').val();
            //     if(check){
            //         $(this).removeClass("fa fa-save").html('<span class="fa fa-spin fa-spinner"></span> Please Wait').attr('disabled',true);
            //     }
            // });
            $('.type').on('change', function () {
                let status = $(this).val();
                if(status==='1'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='2'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('d-none').removeClass('show');
                }
                if(status==='3'){
                    $('.suppliers').addClass('show').removeClass('d-none');
                    $('.members').addClass('d-none').removeClass('show');
                    $('.projects').addClass('show').removeClass('d-none');
                }
                if(status==='4'){
                    $('.suppliers').addClass('d-none').removeClass('show');
                    $('.members').addClass('show').removeClass('d-none');
                    $('.projects').addClass('show').removeClass('d-none');
                }
            });
            $('.t_type').on('change', function () {
                let check = $(this).val();
                if(check==='1'){
                    $('.supplier').addClass('d-none').removeClass('show');
                }
                if(check==='2'){
                    $('.supplier').addClass('show').removeClass('d-none');
                }
            });
        });
    </script>
@endsection
