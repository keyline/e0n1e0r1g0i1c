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
      $center_id              = $row->center_id;
      $name                   = $row->name;
      $address                = $row->address;
      $country_id             = $row->country_id;
      $state_id               = $row->state_id;
      $district_id            = $row->district_id;
      $locality               = $row->locality;
      $pincode                = $row->pincode;
      $landmark               = $row->landmark;
      $email                  = $row->email;
      $phone                  = $row->phone;
      $alt_phone              = $row->alt_phone;
      $whatsapp_no            = $row->whatsapp_no;
      $dob                    = $row->dob;
      $guardian_name          = $row->guardian_name;
      $guardian_relation      = $row->guardian_relation;
      $source_id              = $row->source_id;
      $student_doc_type_id    = $row->student_doc_type_id;
      $student_id_proof       = $row->student_id_proof;
      $guardian_doc_type_id   = $row->guardian_doc_type_id;
      $guardian_id_proof      = $row->guardian_id_proof;
      $student_photo          = $row->student_photo;
      $current_label_id       = $row->current_label_id;
      $current_label_marks    = $row->current_label_marks;
    } else {
      $center_id              = '';
      $name                   = '';
      $address                = '';
      $country_id             = '';
      $state_id               = '';
      $district_id            = '';
      $locality               = '';
      $pincode                = '';
      $landmark               = '';
      $email                  = '';
      $phone                  = '';
      $alt_phone              = '';
      $whatsapp_no            = '';
      $dob                    = '';
      $guardian_name          = '';
      $guardian_relation      = '';
      $source_id              = '';
      $student_doc_type_id    = '';
      $student_id_proof       = '';
      $guardian_doc_type_id   = '';
      $guardian_id_proof      = '';
      $student_photo          = '';
      $current_label_id       = '';
      $current_label_marks    = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="center_id" class="col-md-2 col-lg-2 col-form-label">Center</label>
              <div class="col-md-10 col-lg-10">
                  <select name="center_id" class="form-control" id="center_id" required>
                    <option value="" selected>Select Center</option>
                    <?php if($centers){ foreach($centers as $cnt){?>
                    <option value="<?=$cnt->id?>" <?=(($cnt->id == $center_id)?'selected':'')?>><?=$cnt->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="address" class="col-md-2 col-lg-2 col-form-label">Address</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="address" class="form-control" id="address" rows="3"><?=$address?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="country_id" class="col-md-2 col-lg-2 col-form-label">Country</label>
              <div class="col-md-10 col-lg-10">
                  <select name="country_id" class="form-control" id="country_id" required>
                    <option value="" selected>Select Country</option>
                    <?php if($countries){ foreach($countries as $cn){?>
                    <option value="<?=$cn->id?>" <?=(($cn->id == $country_id)?'selected':'')?>><?=$cn->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="state_id" class="col-md-2 col-lg-2 col-form-label">State</label>
              <div class="col-md-10 col-lg-10">
                  <select name="state_id" class="form-control" id="state_id" required>
                    <option value="" selected>Select State</option>
                    <?php if($states){ foreach($states as $st){?>
                    <option value="<?=$st->id?>" <?=(($st->id == $state_id)?'selected':'')?>><?=$st->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="district_id" class="col-md-2 col-lg-2 col-form-label">District</label>
              <div class="col-md-10 col-lg-10">
                  <select name="district_id" class="form-control" id="district_id" required>
                    <option value="" selected>Select District</option>
                    <?php if($districts){ foreach($districts as $dst){?>
                    <option value="<?=$dst->id?>" <?=(($dst->id == $district_id)?'selected':'')?>><?=$dst->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="locality" class="col-md-2 col-lg-2 col-form-label">Locality</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="locality" class="form-control" id="locality" value="<?=$locality?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="pincode" class="col-md-2 col-lg-2 col-form-label">Pincode</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="pincode" class="form-control" id="pincode" value="<?=$pincode?>" minlength="6" maxlength="6" onkeypress="return isNumber(event)" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="landmark" class="col-md-2 col-lg-2 col-form-label">Landmark</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="landmark" class="form-control" id="landmark" value="<?=$landmark?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="email" class="col-md-2 col-lg-2 col-form-label">Email</label>
              <div class="col-md-10 col-lg-10">
                <input type="email" name="email" class="form-control" id="email" value="<?=$email?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="phone" class="col-md-2 col-lg-2 col-form-label">Phone</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="phone" class="form-control" id="phone" value="<?=$phone?>" minlength="10" maxlength="10" onkeypress="return isNumber(event)" required>
                <p class="mt-3"><input type="checkbox" id="wp_same_as_phone"> &nbsp;&nbsp;<label for="wp_same_as_phone">Copy Number As Whatsapp No.</label></p>
              </div>
            </div>
            <div class="row mb-3">
              <label for="alt_phone" class="col-md-2 col-lg-2 col-form-label">Alt. Phone</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="alt_phone" class="form-control" id="alt_phone" value="<?=$alt_phone?>" minlength="10" maxlength="10" onkeypress="return isNumber(event)">
                <p class="mt-3"><input type="checkbox" id="wp_same_as_altphone"> &nbsp;&nbsp;<label for="wp_same_as_altphone">Copy Number As Whatsapp No.</label></p>
              </div>
            </div>
            <div class="row mb-3">
              <label for="whatsapp_no" class="col-md-2 col-lg-2 col-form-label">Whatsapp No.</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="whatsapp_no" class="form-control" id="whatsapp_no" value="<?=$whatsapp_no?>" minlength="10" maxlength="10" onkeypress="return isNumber(event)" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="dob" class="col-md-2 col-lg-2 col-form-label">DOB</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="dob" class="form-control" id="dob" value="<?=$dob?>" max="<?=date('Y-m-d')?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="guardian_name" class="col-md-2 col-lg-2 col-form-label">Guardian Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="guardian_name" class="form-control" id="guardian_name" value="<?=$guardian_name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="guardian_relation" class="col-md-2 col-lg-2 col-form-label">Guardian Relation</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="guardian_relation" class="form-control" id="guardian_relation" value="<?=$guardian_relation?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="source_id" class="col-md-2 col-lg-2 col-form-label">Source</label>
              <div class="col-md-10 col-lg-10">
                  <select name="source_id" class="form-control" id="source_id" required>
                    <option value="" selected>Select Source</option>
                    <?php if($sources){ foreach($sources as $src){?>
                    <option value="<?=$src->id?>" <?=(($src->id == $source_id)?'selected':'')?>><?=$src->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="student_doc_type_id" class="col-md-2 col-lg-2 col-form-label">Student ID Proof Type</label>
              <div class="col-md-10 col-lg-10">
                  <select name="student_doc_type_id" class="form-control" id="student_doc_type_id" required>
                    <option value="" selected>Select Student ID Proof Type</option>
                    <?php if($docTypes){ foreach($docTypes as $docType){?>
                    <option value="<?=$docType->id?>" <?=(($docType->id == $student_doc_type_id)?'selected':'')?>><?=$docType->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="student_id_proof" class="col-md-2 col-lg-2 col-form-label">Student ID Proof</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="student_id_proof" class="form-control" id="student_id_proof">
                <small class="text-info">* Only PDF & Image files are allowed</small><br>
                <?php if($student_id_proof != ''){?>
                  <a href="<?=env('UPLOADS_URL').'student/'.$student_id_proof?>" target="_blank" class="badge bg-primary" download>View File</a>
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="guardian_doc_type_id" class="col-md-2 col-lg-2 col-form-label">Guardian ID Proof Type</label>
              <div class="col-md-10 col-lg-10">
                  <select name="guardian_doc_type_id" class="form-control" id="guardian_doc_type_id" required>
                    <option value="" selected>Select Guardian ID Proof Type</option>
                    <?php if($docTypes){ foreach($docTypes as $docType){?>
                    <option value="<?=$docType->id?>" <?=(($docType->id == $guardian_doc_type_id)?'selected':'')?>><?=$docType->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="guardian_id_proof" class="col-md-2 col-lg-2 col-form-label">Guardian ID Proof</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="guardian_id_proof" class="form-control" id="guardian_id_proof">
                <small class="text-info">* Only PDF & Image files are allowed</small><br>
                <?php if($guardian_id_proof != ''){?>
                  <a href="<?=env('UPLOADS_URL').'student/'.$guardian_id_proof?>" target="_blank" class="badge bg-primary" download>View File</a>
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="student_photo" class="col-md-2 col-lg-2 col-form-label">Student Photo</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="student_photo" class="form-control" id="student_photo">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($student_photo != ''){?>
                  <img src="<?=env('UPLOADS_URL').'student/'.$student_photo?>" alt="<?=$name?>" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;">
                <?php }?>
              </div>
            </div>

            <div class="row mb-3">
              <label for="current_label_id" class="col-md-2 col-lg-2 col-form-label">Level</label>
              <div class="col-md-10 col-lg-10">
                  <select name="current_label_id" class="form-control" id="current_label_id" required>
                    <option value="" selected>Select Level</option>
                    <?php if($labels){ foreach($labels as $lbl){?>
                    <option value="<?=$lbl->id?>" <?=(($lbl->id == $current_label_id)?'selected':'')?>><?=$lbl->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="current_label_marks" class="col-md-2 col-lg-2 col-form-label">Level Marks</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="current_label_marks" class="form-control" id="current_label_marks" value="<?=$current_label_marks?>">
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

    $("#wp_same_as_phone").change(function() {
      if(this.checked) {
        $('#whatsapp_no').val($('#phone').val());
      } else {
        $('#whatsapp_no').val('');
      }
    });

    $("#wp_same_as_altphone").change(function() {
      if(this.checked) {
        $('#whatsapp_no').val($('#alt_phone').val());
      } else {
        $('#whatsapp_no').val('');
      }
    });
  });
  function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
  }
</script>