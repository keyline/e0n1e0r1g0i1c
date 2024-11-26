<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
    .choices__list--multiple .choices__item {
        background-color: #d81636;
        border: 1px solid #d81636;
    }
</style>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><a href="<?=url('admin/' . $controllerRoute . '/list/')?>"><?=$module['title']?> List</a></li>
      <li class="breadcrumb-item active"><?=$page_header?></li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<section class="section profile">
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
    <?php
    if($row){
      $name                   = $row->name;
      $description            = $row->description;
      $start_date             = $row->start_date;
      $expiry_date            = $row->expiry_date;
      $uploaded_by            = $row->uploaded_by;
      $notice_date            = $row->notice_date;
      $notice_file            = $row->notice_file;
    } else {
      $name                   = '';
      $description            = '';
      $start_date             = '';
      $expiry_date            = '';
      $uploaded_by            = '';
      $notice_date            = '';
      $notice_file            = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="description" class="col-md-2 col-lg-2 col-form-label">Short Description</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="description" class="form-control" id="description" rows="3"><?=$description?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="start_date" class="col-md-2 col-lg-2 col-form-label">Start Date</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="start_date" class="form-control" id="start_date" value="<?=$start_date?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="expiry_date" class="col-md-2 col-lg-2 col-form-label">End Date</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="expiry_date" class="form-control" id="expiry_date" value="<?=$expiry_date?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="notice_date" class="col-md-2 col-lg-2 col-form-label">Notice Date</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="notice_date" class="form-control" id="notice_date" value="<?=$notice_date?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="uploaded_by" class="col-md-2 col-lg-2 col-form-label">Uploaded By</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="uploaded_by" class="form-control" id="uploaded_by" value="<?=$uploaded_by?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="notice_file" class="col-md-2 col-lg-2 col-form-label">Notice File</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="notice_file" class="form-control" id="notice_file">
                <small class="text-info">* Only JPG, JPEG, PNG & PDF files are allowed</small><br>
                <?php if($notice_file != ''){?>
                  <a href="<?=env('UPLOADS_URL').'notice/'.$notice_file?>" target="_blank" class="badge bg-primary" download>View Notice</a>
                <?php }?>
              </div>
            </div>
            
            <div class="text-center">
              <button type="submit" class="btn btn-primary"><?=(($row)?'Save':'Add')?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){    
    var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
        removeItemButton: true,
        maxItemCount:30,
        searchResultLimit:30,
        renderChoiceLimit:30
    });     
  });
</script>