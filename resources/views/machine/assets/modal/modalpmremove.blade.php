<style>
.modal-sm {
    max-width: 80% !important;
}
</style>
<!-- Modal upload -->
<div class="modal fade" id="PMMachineRemove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content ">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ลบรายการตรวจเช็ค</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<form action="{{url('/machine/system/remove')}}" method="post" enctype="multipart/form-data" >
					@csrf
          <div class="card-body">
            <div class="row">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-12 ml-2">
                    <table class="table table-bordered table-head-bg-info table-bordered-bd-info" id="removepmmachine" >
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">รายการ</th>
                          </tr>
                        </thead>
                       <tbody class="data-machine">
                         @foreach($machinepmtemplateremove as $index => $datapmremove)
                           @php
                            $UNID = $datapmremove->UNID;
                           @endphp
                           <tr>
                             <td>{{ $index+1  }}</td>
                             <td>
                               <div class="form-check">
                                 <label class="form-check-label">
                                   <input class="form-check-input add-machine PM_TEMPLATE_UNID_REFREMOVE" type="checkbox" value="{{ $UNID }}"
                                   id="PM_TEMPLATE_UNID_REFREMOVE{{ $UNID }}" name="PM_TEMPLATE_UNID_REFREMOVE{{ $UNID }}">
                                   <span class="form-check-sign">{{$datapmremove->PM_TEMPLATE_NAME}}</span>
                                 </label>
                               </div>
                             </td>
                           </tr>
                         @endforeach
                       </tbody>
                     </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
          <input type="button" class="btn btn-danger delete-confirm" value="ลบ"></input>
      </div>
	      </form>
      </div>
</div>
</div>
