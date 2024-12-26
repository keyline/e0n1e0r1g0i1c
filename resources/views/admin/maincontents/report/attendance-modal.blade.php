<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Attendance: <?=$name?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <h6 class="mb-0"><?=date_format(date_create($attn_date), "M d, Y")?></h6>
            </div>
        </div>
        <div class="status-buttons mb-3">
            <button class="btn btn-outline-danger mb-1">ABSENT</button>
            <button class="btn btn-outline-success mb-1">PRESENT</button>
        </div>
        <div class="mb-3">
            <h6 class="mb-1">Attendance History</h6>
            <?php if($attnDatas){ foreach($attnDatas as $attnData){?>
              <div class="d-flex align-items-center mb-3">
                <img src="<?=$attnData['image']?>" alt="Profile" class="rounded-circle me-3 table_user">
                <div>
                    <p><strong><?=$attnData['time']?></strong> &middot; <?=$attnData['label']?></p>
                    <small class="text-muted"><?=$attnData['address']?></small>
                </div>
              </div>
            <?php } } ?>
        </div>
        <!-- new design on 23-12-2024 -->

         <!-- Card 1 -->
        <div class="odo-card">
            <!-- <div class="card-header">DATE : 09/12/2024</div> -->
            <div class="card-body">
              <h6 class="mb-1">ODO History</h6>

              <div class="timeline-section">
                  <div class="row align-items-center">
                      <div class="col-sm-5">
                          <h5 class="text-success">START</h5>
                          <img src="img.jpeg" alt="start">
                          <p><i class="fa-solid fa-gauge me-2"></i>1995 KM</p>
                          <p><i class="fa-regular fa-clock me-2"></i>12:32 PM</p>
                          <p class="location"><i class="fa-solid fa-location-dot me-2"></i>983, 25th A Cross Rd, HSR Layout</p>
                      </div>
                      <div class="col-sm-2">
                          <div class="distance_box">
                              <i class="fa-solid fa-right-left"></i>
                              <div class="distance">10 KM</div>
                          </div>
                      </div>
                      <div class="col-sm-5">
                          <h5 class="text-danger">END</h5>
                          <img src="img.jpeg" alt="start">
                          <p><i class="fa-solid fa-gauge me-2"></i>1995 KM</p>
                          <p><i class="fa-regular fa-clock me-2"></i>12:32 PM</p>
                          <p class="location"><i class="fa-solid fa-location-dot me-2"></i>983, 25th A Cross Rd, HSR Layout</p>
                      </div>
                  </div>
              </div>
                
            </div>
        </div>
    </div>
  </div>
</div>