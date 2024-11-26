<?php
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
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
      $category_id        = $row->category_id;
      $title              = $row->title;
      $gallery_image      = $row->gallery_image;
    } else {
      $category_id        = '';
      $title              = '';
      $gallery_image      = '';
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="category_id" class="col-md-2 col-lg-2 col-form-label">Gallery Category</label>
              <div class="col-md-10 col-lg-10">
                  <select name="category_id" class="form-control" id="category_id" required>
                    <option value="" selected>Select Gallery Category</option>
                    <?php if($cats){ foreach($cats as $row){?>
                    <option value="<?=$row->id?>" <?=(($row->id == $category_id)?'selected':'')?>><?=$row->name?></option>
                    <?php } }?>
                  </select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="title" class="col-md-2 col-lg-2 col-form-label">Image Title</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="title" class="form-control" id="title" value="<?=$title?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="gallery_image" class="col-md-2 col-lg-2 col-form-label">Gallery Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="gallery_image" class="form-control" id="gallery_image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG files are allowed</small><br>
                <?php if($gallery_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'gallery/'.$gallery_image?>" class="img-thumbnail" alt="<?=$title?>" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$title?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
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