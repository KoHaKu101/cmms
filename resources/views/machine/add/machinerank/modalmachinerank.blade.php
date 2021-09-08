<style>
.modal-sm {
    max-width: 30% !important;
}
</style>
{{-- เพิ่ม Template --}}
<div class="modal fade" id="NewRank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">เพิ่มประเภทรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('machinerank.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="card-body ml-2">
            <div class="row">
              <div class="col-md-6 col-lg-12">  รายการ Rank  </div>
              <div class="col-md-6 col-lg-12 mt-2 has-error">
                <input type="text" class="form-control" id="MACHINE_RANK_CODE" name="MACHINE_RANK_CODE" placeholder="กรุณาใส่ชื่อ Rank" required>
              </div>
            </div>
            <div class="row mt-4">
              <div class="col-md-6 col-lg-12">  ระยะเวลา (เดือน)  </div>
              <div class="col-md-6 col-lg-12 mt-2 has-error">
                <input type="number" class="form-control" id="MACHINE_RANK_MONTH" name="MACHINE_RANK_MONTH" min="1" value="1" required>
              </div>
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
