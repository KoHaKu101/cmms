<div class="modal fade" id="NewMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Formmenu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('menu.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <label for="MENU_NAME">Menu Thai</label>
              <input type="text"  class="form-control" id="MENU_NAME" name="MENU_NAME" placeholder="Menu Thai">
            </div>
            <div class="col-md-6">
              <label for="MENU_EN">Menu English</label>
              <input type="text"  class="form-control" id="MENU_EN" name="MENU_EN" placeholder="Menu English">
            </div>
            <div class="col-md-3">
              <label for="MENU_INDEX">MENU Index</label>
              <input type="number" min="1" value="1" class="form-control" id="MENU_INDEX" name="MENU_INDEX" placeholder="MENU Index">
            </div>
            <div class="col-md-3">
              <label for="MENU_STATUS">MENU Status</label>
              <input type="text"  class="form-control" id="MENU_STATUS" name="MENU_STATUS" >
            </div>
            <div class="col-md-6">
              <label for="MENU_CLASS">MENU Class</label>
              <input type="text"  class="form-control" id="MENU_CLASS" name="MENU_CLASS" placeholder="MENU Class">
            </div>
            <div class="col-md-6">
              <label for="MENU_LINK">MENU Link</label>
              <input type="text"  class="form-control" id="MENU_LINK" name="MENU_LINK" placeholder="MENU Link">
            </div>
            <div class="col-md-6">
              <label for="MENU_ICON">MENU Icon</label>
              <input type="text" class="form-control" id="MENU_ICON" name="MENU_ICON"  placeholder="MENU Icon">

            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
