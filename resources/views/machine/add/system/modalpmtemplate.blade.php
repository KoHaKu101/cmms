<style>
.modal-sm {
    max-width: 30% !important;
}
.modal-ms {
    max-width: 50% !important;
}
</style>
{{-- เพิ่ม Template --}}
<div class="modal fade" id="Newtemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">เพิ่มประเภทรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('pmtemplate.store') }}" method="post" enctype="multipart/form-data" id="FRM_SAVE_TEMPLAT">
        @csrf
        <div class="modal-body">
          <div class="card-body ml-2">
            <div class="row ">
              <div class="col-md-6 col-lg-12">  ชือ ประเภทรายการ(template)  </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-6 col-lg-12 has-error">
                <input type="text" class="form-control" id="PM_TEMPLATE_NAME" name="PM_TEMPLATE_NAME" placeholder="กรุณาใส่ชื่อต้นแบบ(template)">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="BTN_SUBMIT_TEMPLAT">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
{{-- แก้ไข Template --}}
<div class="modal fade" id="Edittemplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">

  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <form action="{{ url('machine/pm/template/updatepmtemplate') }}" method="post" enctype="multipart/form-data">
        @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">แก้ไขชื่อประเภท</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body ml-2">
          <div class="row ">
            <div class="col-md-6 col-lg-12">
              ชือ ประเภทรายการ(template)
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6 col-lg-12 has-error" id="sendpmunid">
            </div>
          </div>
          </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
