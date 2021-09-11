<div class="modal fade" id="ViewImg" tabindex="-1" role="dialog" aria-labelledby="exampleModalLalavel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="title_view">เพิ่มประเภทรายการ</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="owl-demo2" class="owl-carousel owl-theme owl-img-responsive owl-loaded owl-drag">
          <div class="owl-stage-outer">
            <div class="owl-stage" style="transform: translate3d(0px, 0px, 0px); transition: all 0s ease 0s; width: 100%;height:400px">
              <div class="owl-item active" style="width: 100%;height:400px">
                <div class="item">
                  <img class="img-fluid" id="view_img" src="{{ asset('assets/img/no_image1200_900.png') }}" style="width: 100%;height:400px">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
