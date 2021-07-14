<!-- Modal -->

{{-- เพิ่มอะไหล่ --}}
<div class="modal fade" id="modal-sparepart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-content">
        <form action="{{ route('SparPart.Save') }}" method="POST" id="FRM_SPAREPART" name="FRM_SPAREPART">
          @csrf
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="exampleModalLabel">ข้อมูล Sparepart</h5>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label for="SPAREPART_CODE">Sparepart Code</label>
                <input type="text" class="form-control" id="SPAREPART_CODE" name="SPAREPART_CODE">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="SPAREPART_CODE">Stock Min</label>
                <input type="number" class="form-control" id="STOCK_MIN" name="STOCK_MIN" min='0' value="0">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="SPAREPART_COST">Price</label>
                <input type="number" class="form-control" id="SPAREPART_COST" name="SPAREPART_COST" min='0' value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="SPAREPART_CODE">Sparepart Name</label>
                <input type="text" class="form-control" id="SPAREPART_NAME" name="SPAREPART_NAME">
              </div>
            </div>
            <div class="col-md-8">
              <div class="form-group">
                <label for="SPAREPART_CODE">model</label>
                <input type="text" class="form-control" id="SPAREPART_MODEL" name="SPAREPART_MODEL">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="SPAREPART_CODE">Unit</label>
                <input type="text" class="form-control" id="UNIT" name="UNIT">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label for="SPAREPART_CODE">size</label>
                <input type="text" class="form-control" id="SPAREPART_SIZE" name="SPAREPART_SIZE">
              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="comment">Remark</label>
                <textarea class="form-control" id="SPAREPART_REMARK" name="SPAREPART_REMARK" rows="2"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <label for="comment" class="mr-2">Status</label>
              <!-- Rounded switch -->
              <label class="switch">
                <input type="checkbox" id="STATUS" name="STATUS" value="9" checked>
                <span class="slider round"></span>
              </label>
            </div>
          </div>
         </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"id="BTN_SUBMIT">Save changes</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
