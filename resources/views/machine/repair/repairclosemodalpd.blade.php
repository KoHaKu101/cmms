
<style>
.modal-sm {
    max-width: 30% !important;
}
.modal-ms {
    max-width: 50% !important;
}
body.modal-open {
    overflow: visible;
}
</style>



<style>
  .card-stats .card-body {
    padding: 0px!important;
  }
  .modal-body-step{

      overflow-y: auto;
  }
  .sparepart-table .sparepart-action{
    width: 110px;
  }
  .separator-solid{
    border-top: 1px solid #c3c3c3;
    margin: 6px;
    margin-left: -1px;
  }
  .modal-body-step{
      height: 530px;
      overflow-y: auto;
  }
  .badge{
    font-size: 14px;
  }

  @media all and (max-width: 600px) {
      .modal-body-step{
          height: 500px;
          overflow-y: auto;
      }
  }
  @media all and (max-height: 400px){
    .modal-body-step{
        height: 300px;
        overflow-y: auto;
    }

    .text-col{
      top: 0px;
    }
  }
</style>

<div class="modal fade" id="Result" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div id="overlay">
    <div class="cv-spinner">
      <span class="spinner"></span>
    </div>
  </div>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-primary">
        <h5 class="modal-title" id="TITLE_DOCNO_SUB"></h5>
        <button type="button" class="btn btn-sm btn-danger "data-dismiss="modal" ><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body modal-body-step">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="row">
              <div class="col-12 col-md-12">
                  <h4 class="modal-title" id="show-detail">อาการเสีย : {{ Cookie::get('DETAIL')}}</h4>
              </div>
            </div>
          </div>
        </div>
        <div class="separator-solid" ></div>
        <div class="row">
          <div class="col-12 col-md-12  my-1  ">
            <h4 class="modal-title badge  badge-primary my-1 WORK_STEP_5">สรุปผล</h4>
          </div>
        </div>
        <div class="tab-content my-4 ">

          <div class="tab-pane active" id="WORK_STEP_5">
            <div class="row" id="WORK_STEP_RESULT">
            </div>
            <div class="row">
              <div class="col-md-12 col-lg-10 modal-footer" id="stepsave">

              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
