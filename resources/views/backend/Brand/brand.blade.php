@extends('backend.master')
@section('content')
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              @include('backend.Brand.create')
              @include('backend.Brand.edit')
              <h4 class="card-title mb-0">Brand</h4>
              <p data-bs-toggle="modal" data-bs-target="#modalCreateBrand" class="card-description btn btn-primary ">new brand</p>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Brand ID</th>
                    <th>name</th>
                    <th>Category</th>
                    <th>status</th>
                    <th>action</th>
                  </tr>
                </thead>
                <tbody class="BrandList">
                  {{-- <tr>
                    <td>1001</td>
                    <td>Rothana</td>
                    <td>Phone</td>
                    <td>
                        <span class="badge badge-danger">Active</span>
                        <span class="badge badge-danger">Inactive</span>
                    </td>
                    <td>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEditBrand">Edit</button>
                        <button class="btn btn-danger">Delte</button>
                    </td>
                  </tr> --}}
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
    const BrandList = () => {
    $.ajax({
        type: "POST",
        url: "{{ route('brand.list') }}",
        dataType: "json",
        success: function (response) {
            if (response.status == 200) {
                let brand = response.brands;
                let tr = '';
                $.each(brand, function (key, value) {
                    tr += `
                    <tr>
                        <td>${value.id}</td>
                        <td>${value.name}</td>
                        <td>${value.category.name}</td>
                        <td>
                            ${
                                (value.status == 1) ? '<span class="badge badge-danger">Active</span>' : '<span class="badge badge-danger">Inactive</span>'
                            }
                        </td>
                        <td>
                            <button type="button" onclick="BrandEdit(${value.id})" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEditBrand">Edit</button>
                            <button type="button" onclick="BrandDelete(${value.id})"  class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
                    `;
                });
                $(".BrandList").html(tr);
            }
        }
    });
};
 BrandList()
 const BrandDelete = (id) =>{
    if(confirm("Do you want to delete this ?"))
     {
        $.ajax({
            type: "POST",
            url: "{{ route('brand.destroy') }}",
            data: {
                'id':id
            },
            dataType: "json",
            success: function (response) {
            if(response.status ==200)
            {
                BrandList()
                Message(response.message)
            }
            }
        });
     }
 }
 const BrandEdit = (id)=>{
   $.ajax({
    type: "POST",
    url: "{{ route('brand.edit') }}",
    data:{
        'id':id
    },
    dataType: "json",
    success: function (response) {
        if(response.status==200){
            $('.name_edit').val(response.brands.name)
            $('#id').val(response.brands.id)
            $('.status').val(response.brands.status)
        }
    }
   });
 }
 const UpdateBrand = (form) =>{
    let payload = new FormData($(form)[0])
    $.ajax({
        type: "POST",
        url: "{{ route('brand.update') }}",
        data: payload,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if(response.status ==200)
        {
                   $('#modalEditBrand').modal("hide");
                    $(form).trigger("reset");
                    BrandList()
                    Message(response.message)
        }

        }
    });
 }
 UpdateBrand()
      const BrandStore = (form) =>{
        let payload = new FormData($(form)[0])
        $.ajax({
            type: "POST",
            url: "{{route('brand.store')}}",
            data: payload,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status ==200)
                {
                    $('#modalCreateBrand').modal("hide");
                    $(form).trigger("reset");
                    BrandList()
                    Message(response.message)
                    $(".name").removeClass("is-invalid").siblings("p").removeClass("text-danger").text(error.name);
                }else
                {
                    let error =response.error;
                    $(".name").addClass("is-invalid").siblings("p").addClass("text-danger").text(error.name);
                }
            }
        });
      }
  </script>
@endsection
