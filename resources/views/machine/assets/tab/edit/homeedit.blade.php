<div class="tab-pane" id="home-1">
  <div class="row">
    <div class="col-sm-12">
      <div class="jumbotron">
        <div class="col-md-12 col-lg-12">
          <div class="card-header bg-primary">
            <h3 align="center" style="color:white;" class="mt-2">ข้อมูลทั่วไป</h3>
          </div>
      </div>
        <div class="row">
          <div class="col-md-12 col-lg-3">
            <div class="form-group">
              <label for="MACHINE_PARTNO">PartNo</label>
                <input type="text" class="form-control form-control-sm" id="MACHINE_PARTNO" name="MACHINE_PARTNO" value="{{ $dataset->MACHINE_PARTNO }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_PRICE">ราคา	</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_PRICE" name="MACHINE_PRICE" value="{{ $dataset->MACHINE_PRICE }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_POWER">Power</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_POWER" name="MACHINE_POWER" value="{{ $dataset->MACHINE_POWER }}">
            </div>
          </div>
          <div class="col-md-12 col-lg-3">
            <div class="form-group">
              <label for="MACHINE_MODEL">Model</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_MODEL" name="MACHINE_MODEL"  value="{{ $dataset->MACHINE_MODEL }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_MA_COST">ค่าใช้จ่ายซ่อมบำรุง	</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_MA_COST" name="MACHINE_MA_COST"  value="{{ $dataset->MACHINE_MA_COST }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_WEIGHT">Weight	</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_WEIGHT" name="MACHINE_WEIGHT"  value="{{ $dataset->MACHINE_WEIGHT }}">
            </div>
          </div>
          <div class="col-md-12 col-lg-3">
            <div class="form-group">
              <label for="MACHINE_SERIAL">Serial</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_SERIAL" name="MACHINE_SERIAL" value="{{ $dataset->MACHINE_SERIAL }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_SPEED_UNIT">ความเร็ว</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_SPEED_UNIT" name="MACHINE_SPEED_UNIT" value="{{ $dataset->MACHINE_SPEED_UNIT }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_TARGET">Target</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_TARGET" name="MACHINE_TARGET" value="{{ $dataset->MACHINE_TARGET }}">
            </div>
          </div>
          <div class="col-md-12 col-lg-3">
            <div class="form-group">
              <label for="MACHINE_MANU">บริษัทที่ผลิต	</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_MANU" name="MACHINE_MANU" value="{{ $dataset->MACHINE_MANU }}" >
            </div>
            <div class="form-group">
              <label for="MACHINE_SPEED">ความเร็ว</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_SPEED" name="MACHINE_SPEED" value="{{ $dataset->MACHINE_SPEED }}">
            </div>
            <div class="form-group">
              <label for="MACHINE_MTBF">Priority</label>
              <input type="text" class="form-control form-control-sm" id="MACHINE_MTBF" name="MACHINE_MTBF" value="{{ $dataset->MACHINE_MTBF }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
