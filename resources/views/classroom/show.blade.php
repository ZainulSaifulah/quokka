@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">{{$class->name}}</h1>
@stop

@section('content')
    <div class="modal fade" id="join-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Join Class <i class="fas fa-parking-circle-slash"></i></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="classrooms/join" method="POST">
              @csrf
              <div class="modal-body">
                <div class="form-group">
                    <label for="code">Class Code</label>
                    <input type="text" class="form-control" name="code" placeholder="Class Code">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"">Join</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Create Class <i class="fas fa-parking-circle-slash"></i></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="classrooms" method="POST">
              @csrf
              <div class="modal-body">
                <div class="form-group">
                    <label for="name">Class Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Class Name">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"">Create</button>
              </div>
            </form>
          </div>
        </div>
      </div>
@stop
