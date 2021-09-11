<div class="modal fade" id="modal-plansparepartcheck" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-content">
        <form action="{{ route('SparPart.Report.Save')}}" method="POST" id="FRM_CHECKSPAREPART" name="FRM_CHECKSPAREPART">
          @csrf
        <div class="modal-header bg-primary">
          <h5 class="modal-title" id="Title_plansparepartcheck">Machine Code :</h5>
        </div>
        <div class="modal-body modal-planform">
        </div>
         <div class="modal-body">
           <div class="row">
            <div class="my-2 col-md-6 form-inline" id="BTN_CONFIRM">
              <div class="input-group">
             <div class="input-group-prepend">
              <span class="text-white input-group-text bg-info" id="basic-addon3">เลื่อนแผน</span>
              </div>
              <input type="text" class="text-black col-md-8 form-control form-control-sm bg-bluelight" id="PLAN_CHANGE" name="PLAN_CHANGE">
            </div>
           <button type="button" class="btn btn-warning btn-sm btn-confirm">Confirm</button>
            </div>
            <div class="my-2 col-md-6">
              <div class="input-group has-error">
                <div class="input-group-prepend">
                  <span class="text-white input-group-text bg-info" id="basic-addon3">ผู้ทำการเปลี่ยน</span>
                </div>
                <input type="text" class="text-black form-control form-control-sm bg-bluelight"
                 id="USER_CHECK" name="USER_CHECK" required>
              </div>
            </div>
          </div>
         </div>
        <div class="modal-footer" id="FOOTER" name="FOOTER">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-saveform" id="BTN_SAVEFORM" name="BTN_SAVEFORM">Save</button>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
