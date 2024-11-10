@extends('backend.master')
@section('content')

      {{-- Modal create start --}}
      @include('backend.Product.create')
      {{-- Modal create end --}}

      {{-- Modal edit start --}}
      @include('backend.Product.edit')
      {{-- Modal edit start --}}

      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Products</h3>
                <p onclick="HandleClickButtonNewProduct()" data-bs-toggle="modal" data-bs-target="#modalCreateProduct" class="card-description btn btn-primary ">new product</p>
            </div>
            <table class="table table-striped mb-3">
              <thead>
                <tr>
                  <th>Product ID</th>
                  <th>Product Image</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Brand</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Stock</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody class="product_list">

              </tbody>

            </table>
            <div class="d-flex justify-content-between align-items-center">

                <div class="show-page mt-3">

                </div>

                <button onclick="" class=" btn btn-outline-danger rounded-0 btn-sm">refresh</button>

            </div>
          </div>
        </div>
      </div>
@endsection

@section('scripts')
      <script>
        $(document).ready(function(){

            $('.color_add').select2({
                            placeholder: 'Select options',
                            allowClear: true,
                            tags: true,
                         });
                         $('.color_edit').select2({
                            placeholder: 'Select options',
                            allowClear: true,
                            tags: true,
                         });
        })
        const ProductUpload = (form) => {
            let payloads = new FormData($(form)[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('product.uploads') }}",
                data: payloads,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status ==200){
                        Message(response.message)
                        let images = response.image;
                        let img = '';
                        $.each(images, function (key, value) {
                            img += `
                                <div class="col-lg-3 mb-3">
                                <input type="hidden" name="images[]" value="${value}">
                                <img  height="200" class="w-100" src="{{asset('uploads/temp/${value}')}}">
                                <button type="button" onclick="Cancel(this,'${value}')" class="btn btn_cancel btn-danger rounded-0 btn-sm">cancel</button>
                                </div>
                                    `;
                    });
                      $('.show-images').html(img)
                    }

            }
            });
        }
        const Cancel = (e,image) =>{
            if(confirm("do you waant to Cancel this ?")){
                $.ajax({
                type: "Post",
                url: "{{ route('product.cancel') }}",
                data: {
                    'image':image
                },
                dataType: "json",
                success: function (response) {
                if(response.status ==200)
                {
                    Message(response.message)
                    $(e).parent().remove();

                }
                }
            });

            }

        }
        const HandleClickButtonNewProduct = () => {
            $.ajax({
                type: "POST",
                url: "{{ route('product.data') }}",
                data: "data",
                dataType: "json",
                success: function (response) {
                    if(response.status ==200) {
                        let category = response.data.categories;
                        let brand = response.data.brands;
                        let color = response.data.colors;
                        let category_option = '';
                        let brand_option = '';
                        let color_option = '';

                        $.each(category, function (key,value) {
                            category_option +=`
                            	<option value="${value.id}">${value.name}</option>
                            `;

                        });
                        $(".category_add"). html(category_option);


                        $.each(brand, function (key,value) {
                            brand_option +=`
                            	<option value="${value.id}">${value.name}</option>
                            `;

                        });
                        $(".brand_add"). html(brand_option);


                        $.each(color, function (key,value) {
                            color_option +=`
                            	<option value="${value.id}">${value.name}</option>
                            `;

                        });
                        $(".color_add"). html(color_option);

                    }
                }
            });
        }
        const ProductSrore = (form) =>{
            let payload = new FormData($(form)[0])
            $.ajax({
                type: "POST",
                url: "{{ route('product.store') }}",
                data: payload,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function (response) {
                 if(response.status ==200)
                {
                   $(form).trigger('reset');
                   $(".show-images ").html("")
                   $("#modalCreateProduct").modal("hide")
                    Message(response.message)
                    ProductList()
                }else{
                    Message(response.message)
                }
                }
            });
        }
        ProductList = ()=>{
            $.ajax({
                type: "POST",
                url: "{{ route('product.list') }}",
                dataType: "json",
                success: function (response) {
                    if (response.status == 200) {
                let products = response.products;
                let tr = '';
                $.each(products, function (key, value) {
                    tr += `
                        <tr>
                            <td>${value.id}</td>
                            <td>
                                <img src="{{ asset('uploads/product/${value.image[0].image}') }}" alt="${value.name}" ">
                                </td>
                            <td>${value.name}</td>
                            <td>${value.categories.name}</td>
                            <td>${value.brands.name}</td>
                            <td>${value.price}</td>
                            <td>${value.qty}</td>
                            <td>
                                <span class="p-1 badge ${value.qty > 1 ? 'badge-success' : 'badge-danger'}">
                                    ${value.qty > 0 ? "In Stock" : "Out of Stock"}
                                </span>
                            </td>
                            <th>
                                <span class="badge p-1 ${(value.status == 1) ? 'badge-success' : 'badge-danger'}">
                                    ${(value.status == 1) ? "Active" : "Inactive"}
                                </span>
                            </th>
                            <td>
                                <button onclick="edit(${value.id})" type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdateProduct">Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                $(".product_list").html(tr);
            }

                }
            });
        }
        ProductList()
        const edit = (id)=>{
            $.ajax({
                type: "POST",
                url: "{{route('product.edit') }}",
                data: {
                    'id':id
                },
                dataType: "json",
                success: function (response) {
                 if(response.status ==200)
                {
                    let categories = response.data.categories;
                    let category_option ='';
                    $.each(categories, function (key, value) {
                     category_option +=`
                        	<option value="${value.id} ${(value.id ==response.data.product.category_id) ? 'selected' : ''}">
                                ${value.name}
                            </option>
                      `;

                    });
                    $(".category_edit").html(category_option);

                    // brands
                    let brands  = response.data.brands
                    let brands_option = '';
                    $.each(brands, function (key, value) {
                        brands_option +=`
                            	<option value="${value.id}" ${(value.id ==response.data.product.brand_id) ? 'selected' : ''}>
                                ${value.name}
                            </option>
                         `;
                    });
                    $(".brand_edit").html(brands_option);
                    //color
                        let colors = response.data.colors;
                        let color_ids = response.data.product.color; // Assuming this is an array of color IDs
                        let color_option = '';

                        $.each(colors, function (key, value) {
                            if (color_ids.includes(String(value.id))) { // Check if the color ID is in the selected color IDs
                                color_option += `
                                    <option value="${value.id}" selected>
                                        ${value.name}
                                    </option>
                                `;
                            } else {
                                color_option += `
                                    <option value="${value.id}">
                                        ${value.name}
                                    </option>
                                `;
                            }
                        });

                       $(".color_edit").html(color_option);
                    //Images
                    let images = response.data.productImage;
                        let img = '';
                        $.each(images, function (key, value) {
                            img += `
                                <div class="col-lg-3 mb-3">
                                <input type="hidden" name="images[]" value="${value.image}">
                                <img  height="200" class="w-100" src="{{asset('uploads/product/${value.image}')}}">
                                <button type="button" onclick="Cancel(this,'${value.image}')" class="btn btn_cancel btn-danger rounded-0 btn-sm">cancel</button>
                                </div>
                                    `;
                           });
                      $('.show-images-edit').html(img)
                }

                }
            });

        }

      </script>

@endsection
