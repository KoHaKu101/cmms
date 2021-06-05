<div class="tab-pane" id="uploadmanue-1" >
  <div class="row " >
    <div class="col-sm-12 ">
      <div class="jumbotron">
        <div class="col-md-12 col-lg-12">
          <div class="card-header bg-primary">
            <div class="row">
              <div class="col-5 col-sm-6 col-md-8 col-lg-10">
                <h3 align="center" style="color:white;" class="mt-2">รายการเอก/คู่มือ</h3>

              </div>
              <div class="col-5 col-sm-6 col-md-4 col-lg-2">
                <button  id="popup" type="button" class="btn btn-warning float-right btn-sm "
                  data-toggle="modal" data-target="#exampleModal4">
                  <i class="fas fa-cloud-upload-alt" style="color:black;font-size:14px">Upload</i>
                </button>
              </div>
            </div>
          </div>
        </div>



        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
          <div class="table-responsive">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>##</th>
                  <th>รายการเอก/คู่มือ</th>
                  <th>ชื่อไฟล์</th>
                  <th>ประเภทไฟล์</th>
                  <th>ขนาดไฟล์</th>
                  <th></th>
                  <th>วันที่อัปโหลด</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($machineupload as $key =>$uploaditem)
              <tr>
                <td>  {{$key=1 , $key++}} </td>
                <td>  <h5>{{ $uploaditem->TOPIC_NAME }}</h5>  </td>
                <td>  <h5>{{ $uploaditem->FILE_EXTENSION }}</h5>  </td>
                <td>  <i class="fas fa-file-word "></i>  </td>
                <td>
                  <div class="form-group form-inline">
                    <h5>{{ $uploaditem->FILE_SIZE }}</h5>
                    <h5>MB</h5>
                  </div>
                </td>
                <td>
                  <a href="{{ url('machine/assets/uploadpdf/'.$uploaditem->UNID) }}" class="btn btn-primary btn-link">
                    <i class="fas fa-eye fa-lg "></i>
                  </a>
                  <a href="{{ url('upload/manual/'.$dataset->MACHINE_CODE.'/'.$uploaditem->UNID) }}">
                    <button type="button"class="btn btn-success btn-link"><i class="fas fa-download fa-lg"></i>	</button>
                  </a>
                  <a href="{{ url('machine/upload/edit/'.$uploaditem->UNID) }}">
                    <button type="button" class="btn btn-warning btn-link ">
                      <i class="fas fa-edit fa-lg "></i>
                    </button>
                  </a>
                  <a href="{{url('machine/upload/delete/'.$uploaditem->UNID)}}" class="btn btn-danger btn-link">
                    <i class="fas fa-trash fa-lg "></i>	</a>

                </td>

                <td>
                  <small>{{ $uploaditem->FILE_UPLOADDATETIME }}</small>
                </td>
                @endforeach
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
