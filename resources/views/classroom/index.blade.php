@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col">
            <h1 class="m-0 text-dark">Your Classroom</h1>
        </div>
        <div class="col text-right">
            <div class="dropdown">
                <button class="btn btn-primary" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                +
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <button class="dropdown-item" data-toggle="modal" data-target="#join-modal">Join Class</button>
                @if (Auth::user()->role == 'lecturer')
                    <button class="dropdown-item" data-toggle="modal" data-target="#create-modal">Create Class</button>
                @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    @foreach ($userClasses->chunk(3) as $chunk)
        <div class="row">
            @foreach ($chunk as $userClass)
                <div class="col-4">
                    <div class="card">
                        <a href="classrooms/{{$userClass->class_id}}" class="card-header bg-info">
                            <h3>{{$userClass->class->name}}</h3>
                        </a>
                        <div class="card-body">
                            <div class="row">
                                <div class="col text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-link" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @if (Auth::user()->role == 'lecturer')
                                                <button class="dropdown-item" id="delete-class" data-toggle="modal" data-id="{{$userClass->class_id}}" data-target="#delete-modal">Delete Class</button>
                                                <button class="dropdown-item" id="edit-class" data-toggle="modal" data-id="{{$userClass->class_id}}" data-name="{{$userClass->class->name}}" data-target="#edit-modal">Edit Class</button>
                                            @else
                                                <button class="dropdown-item" id="quit-class" data-toggle="modal" data-id="{{$userClass->id}}" data-target="#quit-modal">Quit Class</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

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

    <div class="modal fade" id="quit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Confirmation <i class="fas fa-parking-circle-slash"></i></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="classrooms/quit" method="POST">
              @csrf
              <input type="hidden" id="quit_class_id" name="user_class_id">
              <div class="modal-body">
                  <b>Are you sure to quit this class ?</b>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary"">Quit</button>
              </div>
            </form>
          </div>
        </div>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Confirmation <i class="fas fa-parking-circle-slash"></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="delete_form" action="classrooms/quit" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <b>Are you sure to delete this class ?</b>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"">Yes</button>
            </div>
          </form>
        </div>
      </div>
  </div>

  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Class <i class="fas fa-parking-circle-slash"></i></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="edit_form" action="classrooms/quit" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
              <div class="form-group">
                <label for="name">Class Name</label>
                <input type="text" id="edit_name" class="form-control" name="name" placeholder="Class Name">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary"">Edit</button>
          </div>
        </form>
      </div>
    </div>
</div>
@stop

@section('js')
    <script>
      $(document).ready(function() {
        $('#quit-class').click(function(){
          $('#quit_class_id').val($(this).attr('data-id'));
        });

        $('#edit-class').click(function(){
          id = $(this).attr('data-id')
          $('#edit_form').attr('action' , '/classrooms/'+id);
          $('#edit_name').val($(this).attr('data-name'));
        });

        $('#delete-class').click(function(){
          id = $(this).attr('data-id')
          $('#delete_form').attr('action' , '/classrooms/'+id);
        });

      });
    </script>
@endsection
