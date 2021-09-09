<div class="modal fade" id="NEW_SUBREPAIR" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Title_SUBREPAIR">เพิ่มรายการ</h5>

      </div>
      <form action="{{ route('repairtemplate.subsave') }}" id="FRM_SAVESUB" name="FRM_SAVESUB" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="REPAIR_MAINSELECT_UNIDREF" name="REPAIR_MAINSELECT_UNIDREF">
        <input type="hidden" id="REPAIR_SUBSELECT_UNIDREF" name="REPAIR_SUBSELECT_UNIDREF">
        <div class="modal-body">
          <div class="card-body ml-2">
            <div class="row ">
              <div class="col-md-6 col-lg-12">  รายละเอียดอาการ  </div>
              <textarea class="form-control" id="REPAIR_SUBSELECT_NAME" name="REPAIR_SUBSELECT_NAME"rows="2" required></textarea>
            </div>
            <div class="row">
              <div class="form-group ml-2 CHECKSTATUS">
                <label> สถานะเครื่องจักร </label>
                <select class="form-control from-control-sm" id="STATUS_MACHINE" name="STATUS_MACHINE">
                  @foreach ($MACHINESTATUS as $row_machinestatus)
                    <option value="{{ $row_machinestatus->STATUS_CODE }}"> {{ $row_machinestatus->STATUS_NAME }}</option>
                  @endforeach
                </select>

                </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-12 ml-2">
                <label for="comment" class="mr-2">Status</label>
                <!-- Rounded switch -->
                <label class="switch">
                  <input type="checkbox" class="STATUS" id="STATUS" name="STATUS" value="9" checked>
                  <span class="slider round"></span>
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"  id="SAVE_SUB">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
