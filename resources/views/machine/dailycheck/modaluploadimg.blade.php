
<div class="modal fade" id="UploadImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="title_text">เพิ่มประเภทรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('daily.upload')}}" method="post" enctype="multipart/form-data" id="FRM_UPLOAD_SHEET" name="FRM_UPLOAD_SHEET" >
          @csrf
          <input type="hidden" id="MACHINE_UNID" 					name="MACHINE_UNID" 				value="">
          <input type="hidden" id="MACHINE_CODE" 					name="MACHINE_CODE" 				value="">
          <input type="hidden" id="CHECK_YEAR" 						name="CHECK_YEAR" 					value="">
          <input type="hidden" id="CHECK_MONTH" 					name="CHECK_MONTH" 					value="">
          <div class="row">
            <div class="col-md-12 col-lg-12">
              <div class="form-group">
                <div class="input-group">
                    <input type="file" class="form-control form-control-sm" placeholder="" aria-label="" aria-describedby="basic-addon1"
                    id="FILE_NAME" name="FILE_NAME" accept="application/pdf" required>
                  <div class="input-group-prepend">
                    <button class="btn btn-primary btn-border btn-sm" type="submit"><i class="fa fa-fw fa-upload fa-lg"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
