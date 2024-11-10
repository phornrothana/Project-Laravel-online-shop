@extends('backend.master')
@section('content')
<div class="row">
      {{-- Modal create start --}}
      @include('backend.color.create')
      {{-- Modal create end --}}
      {{-- Modal edit start --}}
      @include('backend.color.edit')
      {{-- Modal edit start --}}
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Colors</h3>
                <p data-bs-toggle="modal" data-bs-target="#modalCreateColor" class="card-description btn btn-primary ">new color</p>
            </div>
            <table class="table table-striped mb-3">
              <thead>
                <tr>
                  <th>Color ID</th>
                  <th>Name</th>
                  <th>Color</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="colors_list">

              </tbody>

            </table>
            <div class="d-flex justify-content-between align-items-center">

                <div class="show-page mt-3">

                </div>

                <button onclick="ColorRefresh()" class=" btn btn-outline-danger rounded-0 btn-sm">refresh</button>

            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('scripts')
<script>

    //Color List
    const ColorList = (page=1,search='') => {
      $.ajax({
        type: "POST",
        url: "{{ route('color.list') }}",
        data : {
            "page" : page,
            "search" : search
        },
        dataType: "json",
        success: function (response) {
            if(response.status == 200){
                let colors = response.colors;
                let tr = ``;
                $.each(colors, function (key,value) {
                    tr += `
                    <tr>
                        <td>B${value.id}</td>
                        <td>${value.name}</td>
                        <th>
                            <div style="background-color:${value.color_code}; height: 20px; width: 20px; border-radius:50%;border:1px solid black;"></div>
                        </th>
                        <th>
                            ${(value.status == 1) ? '<span class="badge badge-success p-1">Active</span>' : ' <span class="badge badge-danger  p-1">Inactive</span>' }
                        </th>
                        <th>
                            <button type="button" onclick="ColorEdit(${value.id},'${value.name}','${value.color_code}',${value.status})" class=" btn btn-info  btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdateColor">Edit</button>
                            <button type="button" onclick="ColorDelete(${value.id})" class="btn btn-danger btn-sm">Delete</button>
                        </th>
                    </tr>
                    `;
                });

                $(".colors_list").html(tr);

                //pagination
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
                                <li onclick="ColorPage(${i})" class="page-item ${(i == currentPage) ? 'active' : '' }">
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

                if(totalPage > 1 ){
                    $(".show-page").html(page);
                }


            }
        }
      });
    }

    //Calling function
    ColorList();


    //Color Refresh
    const ColorRefresh = () => {
        ColorList();
        $("#searchBox").val(" ");
    }


    //Color Page
    const ColorPage = (page) => {
        ColorList(page);
    }


    //Previous Page
    const NextPage  = (page) => {
        ColorList(page + 1);
    }


    //Previous Page
    const PreviousPage = (page) => {
        ColorList(page - 1);
    }

    //Color Search with event
    $(document).on("click",'.searchBtn', function () {
         let searchValue = $("#searchBox").val();
         ColorList(1,searchValue);
         //close modal
         $("#modalSearch").modal('hide');
    });


    //Color Store
    const ColorStore = (form) => {
       let payloads = new FormData($(form)[0]);
       $.ajax({
        type: "POST",
        url: "{{ route('color.store') }}",
        data: payloads,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if(response.status == 200){
                $("#modalCreateColor").modal("hide");
                $(form).trigger('reset');
                $(".name").removeClass("is-invalid").siblings("p").removeClass("text-danger").text(" ");
                Message(response.message);
                ColorList();
            }else{
                let error = response.error;
                $(".name").addClass("is-invalid").siblings("p").addClass("text-danger").text(error.name);
            }
        }
       });
    }


    //Color Edit
    const ColorEdit = (id,name,color_code,status) => {
       $(".name_edit").val(name);
       $("#color_id").val(id);
       $(".color_edit").val(color_code);


        let option = `
            <option value="1" ${(status == 1) ? 'selected' : ''} >Active</option>
            <option value="0" ${(status == 0) ? 'selected' : ''} >Inactive</option>
       `;

        $('.status_edit').html(option);

    }


    //Color Update
    const ColorUpdate = (form) => {
    let payloads = new FormData($(form)[0]);
       $.ajax({
        type: "POST",
        url: "{{ route('color.update') }}",
        data: payloads,
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (response) {
            if(response.status == 200){
                $("#modalUpdateColor").modal("hide");
                $(form).trigger('reset');
                $(".name_edit").removeClass("is-invalid").siblings("p").removeClass("text-danger").text(" ");
                Message(response.message);
                ColorList();
            }else{
                let error = response.error;
                $(".name_edit").addClass("is-invalid").siblings("p").addClass("text-danger").text(error.name);
            }
        }
       });
    }


    //Color Delete
    const ColorDelete = (id) => {
      if(confirm("Do you want to delete this ?")){
        $.ajax({
            type: "POST",
            url: "{{ route('color.destroy') }}",
            data: {
                "id" : id
            },
            dataType: "json",
            success: function (response) {
                if(response.status == 200){
                    Message(response.message);
                    ColorList();
                }
            }
        });
      }
    }
</script>
@endsection
