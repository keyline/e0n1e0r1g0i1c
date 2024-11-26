<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<style type="text/css">
    .choices__list--multiple .choices__item {
        background-color: #132144;
        border: 1px solid #132144;
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
      $doc_type_id            = $row->doc_type_id;
      $id_proof               = $row->id_proof;
      $photo                  = $row->photo;
      $member_since           = $row->member_since;
      $qualification          = $row->qualification;
      $assigned_center_id     = (($row->assigned_center_id != '')?json_decode($row->assigned_center_id):[]);
    } else {
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
      $doc_type_id            = '';
      $id_proof               = '';
      $photo                  = '';
      $member_since           = '';
      $qualification          = '';
      $assigned_center_id     = [];
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
              <label for="password" class="col-md-2 col-lg-2 col-form-label">Password</label>
              <div class="col-md-10 col-lg-10">
                <input type="password" name="password" class="form-control" id="password" <?=((empty($row))?'required':'')?>>
                <?php if($row){?>
                  <small class="text-primary">If you dont want to change password leave the box blank</small>
                <?php }?>
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
              <label for="member_since" class="col-md-2 col-lg-2 col-form-label">Member Since</label>
              <div class="col-md-10 col-lg-10">
                <input type="date" name="member_since" class="form-control" id="member_since" value="<?=$member_since?>" max="<?=date('Y-m-d')?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="qualification" class="col-md-2 col-lg-2 col-form-label">Qualification</label>
              <div class="col-md-10 col-lg-10">
                <textarea name="qualification" class="form-control" id="qualification" rows="2"><?=$qualification?></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label for="choices-multiple-remove-button" class="col-md-2 col-lg-2 col-form-label">Assigned Multiple Centers</label>
              <div class="col-md-10 col-lg-10">
                  <select name="assigned_center_id[]" class="form-control" id="choices-multiple-remove-button" multiple required>
                    <?php if($centers){ foreach($centers as $cnt){?>
                    <option value="<?=$cnt->id?>" <?=((in_array($cnt->id, $assigned_center_id))?'selected':'')?>><?=$cnt->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="doc_type_id" class="col-md-2 col-lg-2 col-form-label">ID Proof Type</label>
              <div class="col-md-10 col-lg-10">
                  <select name="doc_type_id" class="form-control" id="doc_type_id" required>
                    <option value="" selected>Select ID Proof Type</option>
                    <?php if($docTypes){ foreach($docTypes as $docType){?>
                    <option value="<?=$docType->id?>" <?=(($docType->id == $doc_type_id)?'selected':'')?>><?=$docType->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="id_proof" class="col-md-2 col-lg-2 col-form-label">ID Proof</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="id_proof" class="form-control" id="id_proof">
                <small class="text-info">* Only PDF & Image files are allowed</small><br>
                <?php if($id_proof != ''){?>
                  <a href="<?=env('UPLOADS_URL').'teacher/'.$id_proof?>" target="_blank" class="badge bg-primary" download>View File</a>
                <?php }?>
              </div>
            </div>
            <div class="row mb-3">
              <label for="photo" class="col-md-2 col-lg-2 col-form-label">Photo</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="photo" class="form-control" id="photo">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($photo != ''){?>
                  <img src="<?=env('UPLOADS_URL').'teacher/'.$photo?>" alt="<?=$name?>" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;">
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