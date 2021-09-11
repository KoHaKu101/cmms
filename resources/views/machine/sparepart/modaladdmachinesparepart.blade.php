<div class="modal fade" id="modal-machine" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-content">

          <div class="modal-header bg-primary">
            <h5 class="modal-title" id="MODAL_TITLE">เพิ่มรายการ Machine</h5>
            <button type="close" class="btn btn-warning btn-sm" data-dismiss="modal"><i class="fas fa-window-close fa-lg mr-1"></i>Close</button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="SPARPART_CODE"  name="SPARPART_CODE" value="">
            <input type="hidden" id="SPARPART_UNID"  name="SPARPART_UNID" value="">

            <table class="table table-bordered table-head-bg-info table-bordered-bd-info" id="machine_list" name="machine_list">
                <thead>
                  <tr>
                    <th>Machine</th>
                    <th>LINE</th>
                    <th>วาระการเปลี่ยน (เดือน)</th>
                    <th>วันที่เริ่ม</th>
                    <th>จำนวน</th>
                  </tr>
                </thead>
               <tbody class="data-machine">
               </tbody>
             </table>
        </div>
      </div>
    </div>
  </div>
</div>
