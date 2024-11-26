<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section">
  <div class="row">
    <div class="col-xl-12">
      @if(session('success_message'))
        <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('success_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error_message'))
        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show autohide" role="alert">
          {{ session('error_message') }}
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <!-- <h5 class="card-title">
            <a href="{{ url()->previous() }}" class="btn btn-outline-success btn-sm">Back</a>
          </h5> -->
          
          <div class="row">
            <div class="col-md-5">
              <h5>Start Time</h5>
            </div>
            <div class="col-md-5">
              <h5>End Time</h5>
            </div>
            <div class="col-md-2">
              <h5>Action</h5>
            </div>
          </div>
          <form method="POST" action="">
            @csrf
            <div class="field_wrapper">
              <?php if($rows){ foreach($rows as $row){?>
                <div class="row" style="border:1px solid #1321443d; padding: 10px; border-radius: 10px; margin-bottom: 10px;">
                  <div class="col-md-5">
                    <input type="time" name="start_time[]" class="form-control" value="<?=$row->start_time?>" required>
                  </div>
                  <div class="col-md-5">
                    <input type="time" name="end_time[]" class="form-control" value="<?=$row->end_time?>" required>
                  </div>
                  <div class="col-md-2">
                    <a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="fa fa-minus-circle fa-2x text-danger mt-2"></i></a>
                  </div>
                </div>
              <?php } }?>
              <div class="row" style="border:1px solid #1321443d; padding: 10px; border-radius: 10px; margin-bottom: 10px;">
                <div class="col-md-5">
                  <input type="time" name="start_time[]" class="form-control" required>
                </div>
                <div class="col-md-5">
                  <input type="time" name="end_time[]" class="form-control" required>
                </div>
                <div class="col-md-2">
                  <a href="javascript:void(0);" class="add_button" title="Add field"><i class="fa fa-plus-circle fa-2x text-success mt-2"></i></a>
                </div>
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    var maxField = 20; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = `<div class="row" style="border:1px solid #1321443d; padding: 10px; border-radius: 10px; margin-bottom: 10px;">
                        <div class="col-md-5">
                          <input type="time" name="start_time[]" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                          <input type="time" name="end_time[]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                          <a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="fa fa-minus-circle fa-2x text-danger mt-2"></i></a>
                        </div>
                      </div>`; //New input field html 
    var x = 1; //Initial field counter is 1
    
    // Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increase field counter
            $(wrapper).append(fieldHTML); //Add field html
        }else{
            alert('A maximum of '+maxField+' fields are allowed to be added. ');
        }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').parent('div').remove(); //Remove field html
        x--; //Decrease field counter
    });
});
</script>