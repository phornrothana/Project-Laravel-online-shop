@extends('backend.master')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              @include('backend.user.create')
              <h4 class="card-title mb-0">User</h4>
              <p data-bs-toggle="modal" data-bs-target="#modalCreateUser" class="card-description btn btn-primary ">new users</p>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>email</th>
                    <th>Role</th>
                    <th>action</th>
                  </tr>
                </thead>
                <tbody class="userList">
                    {{-- @foreach ($user as $item)
                    <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->role == 1 ? 'Admin' : 'User' }}</td>
                    <td>
                      <a href="#" class="btn btn-primary btn-sm">view</a>
                      <a href="#" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                    </tr>
                    @endforeach --}}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
@section('scripts')
  <script>
        const List = () =>{
            $.ajax({
                type: "POST",
                url: "{{ route('user.list') }}",
                dataType: "json",
                success: function (response) {
                    if(response.status==200){
                        let users = response.users;
                        let tr = '';
                        $.each(users, function (key, value) {
                            tr += `
                                <tr>
                                    <td>${value.id}</td>
                                    <td>${value.name}</td>
                                    <td>${value.email}</td>
                                    <td>${(value.role ==1) ? 'Admin' : 'User' }</td>
                                    <td>
                                    <a href="#" class="btn btn-primary btn-sm">view</a>
                                    <a href="javascript:void()" onclick="DeleteUser(${value.id})" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                               `;
                        });
                        $(".userList").html(tr);
                    }
                }
            });
      }
      List()
        const saveUser = (form) => {
        var payloads = new FormData($(form)[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('user.store') }}",
            data: payloads,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                 if(response.status == 200){
                    $('#modalCreateUser').modal('hide');
                    $("input").removeClass("is-invalid").siblings("p").removeClass("text-danger").text("");
                    $(form).trigger('reset');
                    List();
                    Message(response.message);
                 }
                 else{
                  let errors = response.errors;

                  if(errors.name){
                    $(".name").addClass("is-invalid").siblings('p').addClass("text-danger").text(errors.name);
                  }else{
                    $(".name").addClass("is-invalid").siblings('p').addClass("text-danger").text("");
                  }

                  if(errors.email){
                    $(".email").addClass("is-invalid").siblings('p').addClass("text-danger").text(errors.email);
                  }else{
                    $(".email").addClass("is-invalid").siblings('p').addClass("text-danger").text("");
                  }

                   if(errors.password){
                    $(".password").addClass("is-invalid").siblings('p').addClass("text-danger").text(errors.password);
                  }else{
                    $(".password").addClass("is-invalid").siblings('p').addClass("text-danger").text("");
                  }
                 }
              }
    });
    }

    const DeleteUser = (id) =>{
        if(confirm('Do you want to  Delete this user ? '))
        {
            $.ajax({
                type:"POST",
                url: "{{ route('user.destroy') }}",
                data: {
                    "id" :id
                },
                dataType: "json",
                success: function (response) {
                    if(response.status ==200){
                        List();
                        Message(response.message)
                    }
                }
            });

        }
    }


  </script>
@endsection
