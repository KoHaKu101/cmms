
<div class="tab-pane" id="uploadmanue-1" >
  <div class="row " >
    <div class="col-sm-12 ">
      <div class="jumbotron">
        <div class="card">
          <div class="col-md-12 col-lg-12">
            <div class="card-header bg-primary">
              <div class="row">
                <div class="col-5 col-sm-6 col-md-8 col-lg-10">
                  <h3 align="center" style="color:white;" class="mt-2">รายการเอก/คู่มือ</h3>

                </div>
                <div class="col-5 col-sm-6 col-md-4 col-lg-2">
                  <button  id="popup" type="button" class="btn btn-warning float-right btn-sm "
                    data-toggle="modal" data-target="#UPLOAD_MANUAL">
                    <i class="fas fa-cloud-upload-alt" style="color:black;font-size:14px">Upload</i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
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
                    <td>  <h5>{{ $uploaditem->TOPIC_NAME }}    </h5>      </td>
                    <td>  <h5>{{ $uploaditem->FILE_EXTENSION }}</h5>  </td>
                    <td>  <i class="fas fa-file-word "></i>  </td>
                    <td>
                      <div class="form-group form-inline">
                        <h5>{{ $uploaditem->FILE_SIZE }}</h5>
                        <h5>MB</h5>
                      </div>
                    </td>
                    <td>
                      <button type="button" class="btn btn-primary btn-sm mx-2"
                        onclick="window.open('{{ url('machine/upload/view/'.$uploaditem->UNID) }}', '_blank', 'width=1000,height=1000,resizable=yes,top=100,left=100,menubar=yes,toolbar=yes,scroll=yes');">
                        <i class="fas fa-eye fa-lg "></i>
                      </button>
                      <a href="{{ url('machine/upload/download/'.$uploaditem->UNID) }}">
                        <button type="button"class="btn btn-success btn-link"><i class="fas fa-download fa-lg"></i>	</button>
                      </a>
                        <button type="button" class="btn btn-warning btn-link " onclick="edituploadfile(this)"
                        data-uploadunid="{{ $uploaditem->UNID }}"
                        data-uploadtopicname="{{ $uploaditem->TOPIC_NAME }}">
                          <i class="fas fa-edit fa-lg "></i>
                        </button>
                      <button type="button" class="btn btn-danger btn-link"
                        onclick="deleteupload(this)"
                        data-uploadunid="{{ $uploaditem->UNID }}">
                        <i class="fas fa-trash fa-lg "></i>	</button>
                    </td>
                    <td>
                      <small>{{ $uploaditem->FILE_UPLOADDATETIME }}</small>
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
