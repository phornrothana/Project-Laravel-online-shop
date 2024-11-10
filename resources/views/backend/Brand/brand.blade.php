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
              <div class="show-page mt-3">

              </div>
              <button onclick="BrandRefresh()" class=" btn btn-outline-danger rounded-0 btn-sm">refresh</button>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
@section('scripts')
  <script>
    const BrandList = (page =1,search='') => {
    $.ajax({
        type: "POST",
        url: "{{ route('brand.list') }}",
        data:{
            'page' :page,
            "search" : search
        },
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

                //Pagination
                let page = ``;
                let totalPage = response.page.totalPage;
                let currentPage = response.page.currentPage;
                page = `
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li onclick="PreviousPage(${currentPage})" class="page-item ${(currentPage == 1) ? 'd-none' : 'd-block' }">
                        <a class="page-link" href="javascript:void()" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>`;

                        for(let i=1;i<=totalPage;i++){
                            page += `
                                <li onclick="BrandPage(${i})" class="page-item ${(i == currentPage) ? 'active' : '' }">
                                    <a class="page-link" href="javascript:void()">${i}</a>
                                </li>`;
                        }

                        page +=`<li onclick="NextPage(${currentPage})" class="page-item ${( currentPage == totalPage ) ? 'd-none' : 'd-block'}">
                        <a class="page-link" href="javascript:void()" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </nav>
                `;
                 $(".show-page").html(page)
            }
        }
    });
};
 BrandList()
 const BrandRefresh = () => {
        BrandList();
        $("#search").val(" ");
    }

    $(document).on("click",'.btnSearch', function () {
         let searchValue = $("#search").val();
         BrandList(1,searchValue);

         //close modal
         $("#modalSearch").modal('hide');
    });

    const NextPage  = (page) => {
        BrandList(page + 1);
    }

    //Previous Page
    const PreviousPage = (page) => {
        BrandList(page - 1);
    }

 const BrandPage = (page) => {
    BrandList(page)
 }
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
