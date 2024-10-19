@extends('backend.master')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              @include('backend.category.create')
              @include('backend.category.edit')
              <h4 class="card-title mb-0">User</h4>
              <p data-bs-toggle="modal" data-bs-target="#modalCreateCategory" class="card-description btn btn-primary ">new users</p>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Category ID</th>
                    <th>Image</th>
                    <th>name</th>
                    <th>status</th>
                    <th>action</th>
                  </tr>
                </thead>
                <tbody class="CategorList">

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
    const UploaImage = (form) =>{
        let payload = new FormData($(form)[0]);
        $.ajax({
            type: "POST",
            url: "{{route('category.upload')}}",
            data: payload,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status ==200){
                    let img = `
                          <input type="hidden" name="category-image" value="${response.image}">
                          <img style="width:100%;" src="{{asset('uploads/temp/${response.image}')}}">
                          <button type="button" onclick="Cancel('${response.image}')" class="btn btn_cancel btn-danger rounded-0 btn-sm">cancel</button>
                    `;
                    $('.show-image-category').html(img)
                    $(form).trigger('reset');
                }else{
                    let error =response.error;
                    $('.image').addClass('is-invalid').silbling('p').addClass('text-danger').text( response.error.image);
                }
            }
        });
    }
    const Cancel = (img) =>{
      if(confirm("Do you to want to dele"))
      {
        $.ajax({
            type: "POST",
            url: "{{route('category.cancel')}}",
            data:{
                "image":img
            },
            dataType: "json",
            success: function (response) {
                if(response.status ==200)
            {
                $('.show-image-category').html("")
                Message(response.message);
            }
            }
        });
      }
    }
    const StoreCategory = (form)=>{
        let payload =new FormData($(form)[0]);
        $.ajax({
            type: "POST",
            url: "{{route('category.store')}}",
            data:payload,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status == 200){
                            List()
                            Message(response.message);
                            $("#modalCreateCategory").modal('hide');
                            $(form).trigger('reset');
                            $(".show-image-category").html("")
                 }
            }
        });

    }
    const List = ()=>{
        $.ajax({
            type: "POST",
            url: "{{ route('category.list') }}",
            dataType: "json",
            success: function (response) {
                let category = response.categories;
                let tr = '';
                $.each(category, function (key, value) {
                            tr += `
                                <tr>
                                    <td>${value.id}</td>
                                    <td>
                                      <img src="${value.image}" alt="Category Image"">
                                    </td>
                                    <td>${value.name}</td>
                                    <td>${(value.status ==1) ? 'Admin' : 'User' }</td>
                                    <td>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdateCategory"  onclick="CategoryEdit(${value.id})" class="btn btn-primary btn-sm">Edit</button>
                                    <a href="javascript:void()" onclick="DeteleCategory(${value.id})"  class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                               `;
                 });
                 $('.CategorList').html(tr)
            }
        });
    }
    List()
    const DeteleCategory = (id)=>{
        if(confirm('Do you want to  Delete this category ? '))
        {
            $.ajax({
                type:"POST",
                url: "{{ route('category.destroy') }}",
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
    const CategoryEdit = (id) =>{
        $.ajax({
            type: "POST",
            url: "{{ route('category.edit') }}",
            data: {
                'id':id
            },
            dataType: "json",
            success: function (response) {
    console.log(response); // Check the response structure
    if (response.status == 200) {
        if (response.categories) {
            $(".name_edit").val(response.categories.name);
            $("#id").val(response.categories.id)
            if (response.categories.image) {
                const imagePath = response.categories.image.replace(/\\/g, '/');
                let img = `
                    <input type="hidden" name="old_image" value="${imagePath}">
                    <img style="width:100%;" src="${imagePath}">
                `;
                $('.show-image-edit').html(img);
            }

            $(".status").val(response.categories.status);
        } else {
            console.error("Categories object is undefined.");
        }
    } else {
        console.error("Error fetching category:", response.message);
    }
   }
 });
    }

    const UpdateCategory = (form)=>{
        let payload =new FormData($(form)[0]);
        $.ajax({
            type: "POST",
            url: "{{route('category.update')}}",
            data:payload,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status == 200){
                            List()
                            Message(response.message);
                            $("#modalUpdateCategory").modal('hide');
                            $(form).trigger('reset');
                            $(".show-image-edit").html("")
                 }
            }
        });

    }
    UpdateCategory()
  </script>
@endsection

