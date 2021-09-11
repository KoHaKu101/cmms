<div class="modal fade" id="modal-plansparepartcheck-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-content">
        <form action="{{ route('SparPart.Report.SaveImg') }}" id="FRM_SPAREPART_UPLOAD" name="FRM_SPAREPART_UPLOAD" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
              <h5 class="modal-title" id="Title_IMG">Machine Code :</h5>
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
          <div class="modal-body">
            <div class="col col-lg-12 form-inline">
              <div class="mx-1 form-group">
                <label for="exampleFormControlFile1">แนบรูปภาพปฏิบัติงาน</label>
                <input type="file" class="my-1 form-control-file" id="IMG_SPAREPART_FILE_NAME"
                name="IMG_SPAREPART_FILE_NAME" accept="image/*" required>
                <input type="hidden" id="IMG_SPAREPART_UNID"name="IMG_SPAREPART_UNID"value="">
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <div class="col col-lg-12">
              <button class="btn btn-primary btn-block" id="BTN_UPLOAD" name="BTN_UPLOAD"
              type="submit">Upload</button>
            </div>
          </div>
          <div class="card-body" id="IMG_SHOW" name="IMG_SHOW">

              </div>
        </form>


      </div>
    </div>
  </div>
</div>
