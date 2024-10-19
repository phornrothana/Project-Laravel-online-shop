<div class="modal fade" id="modalCreateBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:40%;">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Create Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <form method="POST" class="formCreatBrand" enctype="multipart/form-data">
                <div class="form-group">
                   <label for="">name</label>
                   <input type="text" name="name" class="name form-control" >
                   <p></p>
                </div>
                <div class="form-group">
                    <label for="">Category</label>
                    <select name="category" class="category form-control">
                        @foreach ($category as $item)
                            <option value="{{ @$item->id}}">{{ @$item->name }}</option>
                        @endforeach
                    </select>
                    <p></p>
                  </div>
                <br>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="status form-control">
                        <option value="1">Active</option>
                        <option value="0">InActive</option>
                    </select>
                    <p></p>
                  </div>
           </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           <button type="button" class=" btn btn-success" onclick="BrandStore('.formCreatBrand')">Save</button>
        </div>
      </div>
    </div>
</div>
