<style>
.modal-lm {
		max-width: 70% !important;
}
.sparepart-table .sparepart-action{
	width: 110px;
}
.separator-solid{
	border-top: 1px solid #c3c3c3;
	margin: 6px;
	margin-left: -1px;
}
</style>
{{-- เพิ่มเครื่องจักร --}}

<div class="modal fade" id="Result" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
	<div class="modal-dialog modal-lm" role="document">
		<div class="modal-content ">
			<form action="#" method="POST" enctype="multipart/form-data" id="FRM_SAVE_REC">
			 @csrf
			 <input type="hidden" id="DOC_ITEMOUT_UNID" name="DOC_ITEMOUT_UNID">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">รายละเอียด</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="SHOW_RESULT">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary " onclick="PrintDoc(this)" id="PRINT_PR" data-unid="">
						<i class="fas fa-print mx-2"></i>Print
					</button>
					<button type="button" class="btn btn-primary " onclick="Save_Rec(this)" id="BTN_SAVE_REC" data-status="">

					</button>
				</div>
			</form>
		</div>
	</div>
	<div id="overlay">
		<div class="cv-spinner">
			<span class="spinner"></span>
		</div>
	</div>
</div>
