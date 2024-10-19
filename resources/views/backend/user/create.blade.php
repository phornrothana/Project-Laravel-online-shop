<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:40%;">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Creating Users</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           <form method="POST" class="formCreateUser">
                <div class="form-group">
                   <label for="">username</label>
                   <input type="text" name="name" class="name form-control" >
                   <p></p>
                </div>
                <div class="form-group">
                  <label for="">email</label>
                  <input type="email" name="email" class="email form-control" >
                  <p></p>
                </div>
                <div class="form-group">
                  <label for="">password</label>
                  <input type="password" name="password" class="password form-control" >
                  <p></p>
                </div>
                <div class="form-group">
                    <label for="">Role</label>
                    <select name="role" class="role form-control">
                        <option value="1">Admin</option>
                        <option value="0">User</option>
                    </select>
                    <p></p>
                  </div>
           </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
           <button type="button" class=" btn btn-success" onclick="saveUser('.formCreateUser')">Save</button>
        </div>
      </div>
    </div>
</div>
