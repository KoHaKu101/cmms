<style>
.modal-sm {
    max-width: 80% !important;
}
</style>
<!-- Modal upload -->
<div class="modal fade" id="UPLOAD_MANUAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content ">
      <form action="{{ route('machine.storeupload') }}" method="POST" enctype="multipart/form-data" id="FRM_UPLOAD_MANUAL" name="FRM_UPLOAD_MANUAL">
        @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

  				<div class="row">
  					<div class="col-md-12 col-lg-12">
  						<div class="form-group">
  							<label for="TOPIC_NAME">ชื่อรายการเอก/คู่มือ</label>
  								<input type="text" class="form-control form-control-sm" id="TOPIC_NAME" name="TOPIC_NAME" placeholder="ชื่อคู่มือ">
                  <input type="hidden" class="form-control" id="MACHINE_UNID" name="MACHINE_UNID"  value="{{ $dataset->UNID }}">
                  <input type="hidden"  id="UPLOAD_MANUAL_UNID" name="UPLOAD_MANUAL_UNID"  >
  						</div>
  					</div>
  				</div>

  			  <div class="row">
  			      <div class="col-md-12 col-lg-12">
  				       <div class="form-group">
  				 	        <label for="FILE_UPLOAD">Example file input</label>
                	  <input type="file" class="form-control-file" id="FILE_UPLOAD" name="FILE_UPLOAD" accept="application/pdf" required >
  					     </div>
  				    </div>
  			  </div>
            </div>
		        <div class="modal-footer">
  	           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	             <button type="submit" class="btn btn-primary"  id="BTN_SUBMIT_MANUAL">Save changes</input>
            </div>
	      </form>
      </div>
</div>
</div>
