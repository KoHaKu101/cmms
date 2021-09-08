<style>
.modal-sm {
    max-width: 30% !important;
}
</style>
{{-- เพิ่ม Template --}}
<div class="modal fade" id="EditRank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">เพิ่มประเภทรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ url('machine/machinerank/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="card-body ml-2" id='datarank'></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
