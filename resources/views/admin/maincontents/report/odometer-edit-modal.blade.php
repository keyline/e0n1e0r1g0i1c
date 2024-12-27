 <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Attendance & Odo Info : <?=$name?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">        
         <!-- Card 1 -->
         <div class="odo-card">
            <!-- <div class="card-header">DATE : 09/12/2024</div> -->
            <div class="card-body">
              <h6 class="mb-1"><u>ODO History</u></h6>
              <?php if($odometer_data){?>
                <div class="timeline-section mb-3" style="border: 1px solid #0096885e;padding: 10px;border-radius: 10px;background-color: #055e1303;">                  
                  <div class="row align-items-center">
                    <div class="col-sm-5">
                        <h5 class="text-success">START</h5>
                        <img src="<?=$odometer_data['start_image']?>" alt="start" style="width: 50px; height:50px; border-radius: 50%;">
                        <p><i class="fa-solid fa-gauge me-2"></i><?=$odometer_data['start_km']?> KM</p>
                        <p><i class="fa-regular fa-clock me-2"></i><?=$odometer_data['start_timestamp']?></p>
                        <p class="location"><small class="text-muted"><i class="fa-solid fa-location-dot me-2"></i><?=$odometer_data['start_address']?></small></p>
                    </div>
                    <?php if($odometer_data['end_km'] > 0){?>
                      <div class="col-sm-2">
                          <div class="distance_box">
                              <i class="fa-solid fa-right-left"></i>
                              <div class="distance"><?=$odometer_data['travel_distance']?> KM</div>
                          </div>
                      </div>
                      <div class="col-sm-5">
                          <h5 class="text-danger">END</h5>
                          <img src="<?=$odometer_data['end_image']?>" alt="start" style="width: 50px; height:50px; border-radius: 50%;">
                          <p><i class="fa-solid fa-gauge me-2"></i><?=$odometer_data['end_km']?> KM</p>
                          <p><i class="fa-regular fa-clock me-2"></i><?=$odometer_data['end_timestamp']?></p>
                          <p class="location"><small class="text-muted"><i class="fa-solid fa-location-dot me-2"></i><?=$odometer_data['end_address']?></small></p>
                      </div>
                    <?php }?>
                  </div>
                </div>
              <?php  } ?>
            </div>
        </div>
    </div>
  </div>
</div> 

<!-- <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">New message</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form>
        <div class="form-group">
          <label for="recipient-name" class="col-form-label">Recipient:</label>
          <input type="text" class="form-control" id="recipient-name">
        </div>
        <div class="form-group">
          <label for="message-text" class="col-form-label">Message:</label>
          <textarea class="form-control" id="message-text"></textarea>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary">Send message</button>
    </div>
  </div>
</div> -->