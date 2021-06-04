<style>
.modal-sm {
    max-width: 80% !important;
}
</style>
<!-- PM insert -->
<div class="modal fade" id="PMMachine" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content ">
      <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">รายการตรวจเช็ค</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<form action="{{url('/machine/system/check/storelist')}}" method="POST" enctype="multipart/form-data" >
					@csrf
          <input type="hidden" id="MACHINE_CODE" name="MACHINE_CODE" value="{{ $dataset->MACHINE_CODE }}" >

          <div class="col-md-8 col-lg-12 ml-2">
            <table class="table table-bordered table-head-bg-info table-bordered-bd-info" id="addpmmachine" >
                <thead>
                  <tr>
                    <th >#</th>
                    <th scope="col">รายการ</th>
                  </tr>
                </thead>
               <tbody class="data-machine">
                 @foreach($machinepmtemplate as $index => $datapm)
                   <tr>
                     <td>
                       {{ $index+1  }}
                     </td>
                     <td><div class="form-check">
                     <label class="form-check-label">
                       <input class="form-check-input add-machine" type="checkbox" value="{{ $datapm->UNID }}"
                       id="PM_TEMPLATE_UNID_REF[]" name="PM_TEMPLATE_UNID_REF[]"
                       >
                       <span class="form-check-sign">{{$datapm->PM_TEMPLATE_NAME}}</span>
                     </label>
                   </div></td>
                   </tr>
                 @endforeach
               </tbody>
             </table>
          </div>

            </div>
		        <div class="modal-footer">
  	           <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
	              <input type="submit" class="btn btn-primary" value="บันทึก"></input>
            </div>
	      </form>
      </div>
</div>
</div>
{{-- *************************************************************** --}}
