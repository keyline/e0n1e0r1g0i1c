<?php
use App\Helpers\Helper;
$controllerRoute                = $module['controller_route'];
?>
<div class="pagetitle">
  <h1><?=$page_header?></h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?=url('admin/dashboard')?>">Home</a></li>
      <li class="breadcrumb-item active"><a href="<?=url('admin/' . $controllerRoute . '/list/')?>"><?=$module['title'].''.$slug?> List</a></li>
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
      $name         = $row->name;
      $email        = $row->email;
      $alt_email    = $row->alt_email;
      $mobile       = $row->mobile;      
      $parentId     = $row->parent_id;
      $roleId       = $row->role_id;
      $roleId       = $row->role_id;
    } else {
      $name         = '';
      $email        = '';
      $alt_email    = '';
      $mobile       = '';
      $parentId       = '';
      $employee_type       = '';
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
                <label for="employee_type" class="col-md-2 col-lg-2 col-form-label">Employee Type</label>
                <div class="col-md-10 col-lg-10">                                                                
                  <input type="text" name="employee_type" class="form-control" id="employee_type" value="<?=$slug?>" readonly>                                         
                </div>
            </div> 
            <div class="row mb-3">
                <label for="parent_id" class="col-md-2 col-lg-2 col-form-label">Parent Employee</label>
                <div class="col-md-10 col-lg-10">      
                  <?php  if($employee_department->level != 1) {
                    if($employee_department->level == 2) {?>                                                          
                  <select name="parent_id" class="form-control" id="parent_id" required>
                      <option value="" selected disabled>Select</option>                      
                      @if ($parent_id)                      
                          @foreach ($parent_id as $data)
                              <option value="{{ $data->id }}" @selected($data->id == $parentId)>
                                  {{ $data->name }}</option>
                          @endforeach
                      @endif
                  </select>  
                  <?php } } else {?>  
                    <input type="text" name="parent_id" class="form-control" id="parent_id" value="0" readonly>
                    <?php } ?>                     
                </div>
            </div> 
            <div class="row mb-3">
              <label for="email" class="col-md-2 col-lg-2 col-form-label">Email</label>
              <div class="col-md-10 col-lg-10">
                <input type="email" name="email" class="form-control" id="email" value="<?=$email?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="alt_email" class="col-md-2 col-lg-2 col-form-label">alt_email</label>
              <div class="col-md-10 col-lg-10">
                <input type="alt_email" name="alt_email" class="form-control" id="alt_email" value="<?=$alt_email?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="mobile" class="col-md-2 col-lg-2 col-form-label">Mobile</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="mobile" class="form-control" id="mobile" value="<?=$mobile?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="password" class="col-md-2 col-lg-2 col-form-label">Password</label>
              <div class="col-md-10 col-lg-10">
                <input type="password" name="password" class="form-control" id="password" value="" <?=((!empty($row))?'':'required')?>>
                <?php if($row){?><small class="text-info">* Leave blank if you don't want to change password</small><br><?php }?>
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