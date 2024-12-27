<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Attendance & Odo Info : <?=$name?></h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="d-flex align-items-center mb-3">
          <div>
            <h6 class="mb-0"><?=date_format(date_create($attn_date), "M d, Y")?></h6>
          </div>
        </div>
         <!-- Card 1 -->
        <div class="odo-card">
            <!-- <div class="card-header">DATE : 09/12/2024</div> -->
            <div class="card-body">
              <h6 class="mb-1"><u>ODO History</u></h6>
              <?php if($odometer_data){ foreach($odometer_data as $odometer_row){?>
                <div class="timeline-section mb-3" style="border: 1px solid #0096885e;padding: 10px;border-radius: 10px;background-color: #055e1303;">
                  <button type="button" class="btn btn-primary btn-xs" style="float: right;" onclick="openEditModal(<?=$odometer_row['id']?>,'<?=$name?>')"><i class="fa fa-edit"></i> Edit<?=$name?></button>
                  <div class="row align-items-center">
                    <div class="col-sm-5">
                        <h5 class="text-success">START</h5>
                        <img src="<?=$odometer_row['start_image']?>" alt="start" style="width: 50px; height:50px; border-radius: 50%;">
                        <p><i class="fa-solid fa-gauge me-2"></i><?=$odometer_row['start_km']?> KM</p>
                        <p><i class="fa-regular fa-clock me-2"></i><?=$odometer_row['start_timestamp']?></p>
                        <p class="location"><small class="text-muted"><i class="fa-solid fa-location-dot me-2"></i><?=$odometer_row['start_address']?></small></p>
                    </div>
                    <?php if($odometer_row['end_km'] > 0){?>
                      <div class="col-sm-2">
                          <div class="distance_box">
                              <i class="fa-solid fa-right-left"></i>
                              <div class="distance"><?=$odometer_row['travel_distance']?> KM</div>
                          </div>
                      </div>
                      <div class="col-sm-5">
                          <h5 class="text-danger">END</h5>
                          <img src="<?=$odometer_row['end_image']?>" alt="start" style="width: 50px; height:50px; border-radius: 50%;">
                          <p><i class="fa-solid fa-gauge me-2"></i><?=$odometer_row['end_km']?> KM</p>
                          <p><i class="fa-regular fa-clock me-2"></i><?=$odometer_row['end_timestamp']?></p>
                          <p class="location"><small class="text-muted"><i class="fa-solid fa-location-dot me-2"></i><?=$odometer_row['end_address']?></small></p>
                      </div>
                    <?php }?>
                  </div>
                </div>
              <?php } } ?>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- <div class="modal fade drawer" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"> -->  
<div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
</div>
<script>
  function openEditModal(odometer_id, name){   
    // $('#edit_modal').modal('show'); 
    // alert(odometer_id);
    $.ajax({
      url: '<?php echo url('admin/report/edit-odometer-details')?>',
      type: 'POST',
      data: {
              "_token": "{{ csrf_token() }}",
              odometer_id: odometer_id,
              name: name,
      },
      dataType: 'html',
      success: function(response){
        $('#edit_modal').html(response);
        $('#edit_modal').modal('show');
      }
    });
  }
</script>