<style>
    .modal-ms {
        max-width: 50% !important;
    }

    .sparepart-table .sparepart-action {
        width: 110px;
    }

    .separator-solid {
        border-top: 1px solid #c3c3c3;
        margin: 6px;
        margin-left: -1px;
    }

</style>
{{-- เพิ่มเครื่องจักร --}}

<div class="modal fade" id="NewPr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel"
    aria-hidden="true">
    <div class="modal-dialog modal-ms" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">สร้างเอกสารนำของออกข้างนอก</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-md-12 ">
                        <h4 class="modal-title badge my-1 badge-primary fw-bold" id="header_step1">รายละเอียด</h4>
                        <i class="separator mx-2">
                            <i class="fas fa-arrow-right"></i>
                        </i>
                        <h4 class="modal-title badge my-1 " id="header_step2">รายการ</h4>
                        <i class="separator mx-2">
                            <i class="fas fa-arrow-right"></i>
                        </i>
                        <h4 class="modal-title badge my-1 " id="header_step3">สรุปผล</h4>
                    </div>
                </div>
                <style>
                    /* The container */
                    .container {
                        display: block;
                        position: relative;
                        padding-left: 35px;
                        margin-bottom: 12px;
                        font-size: 22px;
                        -webkit-user-select: none;
                        -moz-user-select: none;
                        -ms-user-select: none;
                        user-select: none;
                    }

                    /* Hide the browser's default checkbox */
                    .container input[type=radio] {
                        position: absolute;
                        opacity: 0;
                        cursor: pointer;
                        height: 0;
                        width: 0;
                    }

                    /* Create a custom checkbox */
                    .checkmark {
                        cursor: pointer;
                        position: absolute;
                        top: 0;
                        left: 0;
                        height: 25px;
                        width: 25px;
                        background-color: #eee;
                    }

                    /* On mouse-over, add a grey background color */
                    .container:hover input~.checkmark {
                        background-color: #ccc;
                    }

                    /* When the checkbox is checked, add a blue background */
                    .container input:checked~.checkmark {
                        background-color: #2196F3;
                    }

                    /* Create the checkmark/indicator (hidden when not checked) */
                    .checkmark:after {
                        content: "";
                        position: absolute;
                        display: none;
                    }

                    /* Show the checkmark when checked */
                    .container input:checked~.checkmark:after {
                        display: block;
                    }

                    /* Style the checkmark/indicator */
                    .container .checkmark:after {
                        left: 9px;
                        top: 5px;
                        width: 5px;
                        height: 10px;
                        border: solid white;
                        border-width: 0 3px 3px 0;
                        -webkit-transform: rotate(45deg);
                        -ms-transform: rotate(45deg);
                        transform: rotate(45deg);
                    }

                    .has-error label {
                        color: #6c757d !important;
                    }

                </style>
                <div class="tab-content my-4">
                    <div class="tab-pane active" id="step1">
                        <form action="#" id="FRM_SAVE_STEP1" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 ml-auto has-error ">
                                    <label class="my-2">ประเภท</label>
                                    <div class="row ">
                                        <label class="container col-md-5">ขาย
                                            <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="SELL">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="container col-md-5">ขอยืม/ส่งซ่อม
                                            <input type="radio" name="DOC_TYPE" id="DOC_TYPE" value="OUT" checked>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>

                                </div>
                                <div class="col-md-4 mr-auto has-error">
                                    <label class="my-2">วันที่</label>
                                    <input type="date" class="form-control form-control-sm" id="DOC_DATE"
                                        name="DOC_DATE" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6 ml-auto has-error">
                                    <label class="my-2">บริษัท</label>
                                    <select class="form-control form-control-sm" id="COMPANY_UNID" name="COMPANY_UNID">
                                    </select>
                                </div>

                                <div class="col-md-4 mr-auto has-error">
                                    <label class="my-2">ผู้นำออก</label>
                                    <select class="form-control form-control-sm" id="EMP_UNID" name="EMP_UNID">
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="step2">
                        <form action="#" method="post" enctype="multipart/form-data" id="FRM_SAVE_STEP2">
                            @csrf
                            <input type="hidden" id="DOC_ITEM_UNID" name="DOC_ITEM_UNID">
                            <div id="FRM_STEP2">
                            </div>
                            <div>
                                <div class="separator-solid"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table">
                                            <label>รายการทั้งหมด</label>
                                            <table
                                                class="table table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">ลำดับ</th>
                                                        <th width="55%">รายการ</th>
                                                        <th width="10%">จำนวน</th>
                                                        <th width="10%">หน่วย</th>
                                                        <th width="20%">กำหนดส่งคืน</th>
                                                        <th width="15%">action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="result_detail">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="step3">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: space-around">
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <button type="button" class="btn btn-primary " id="BTN_PREVIOUS" data-previous="0" hidden><i
                                class="fas fa-arrow-left"></i></button>
                    </div>
                    <div class="col-md-6 mr-auto">
                        <button type="button" class="btn btn-primary " id="BTN_NEXT" data-next="2"><i
                                class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
</div>
