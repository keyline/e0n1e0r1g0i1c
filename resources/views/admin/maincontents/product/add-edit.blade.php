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
      $name           = $row->name;
      $short_desc     = $row->short_desc;
      $product_image  = $row->product_image;
      $packageunitId         = $row->package_size_unit;      
      $package_size         = $row->package_size;
      $case_size         = $row->case_size;
      $caseunitId         = $row->case_unit;
      $case_qty         = $row->per_case_qty;
      $caseqtyunitId         = $row->per_case_qty_unit;
      $gst_percent         = $row->gst_percent;
      $invoice_rate_per_unit         = $row->invoice_rate_per_unit;
      $mrp_per_unit         = $row->mrp_per_unit;
      $product_catId  = $row->category_id;
      $unitId         = $row->unit_id;
      $sizeId         = $row->size_id;
    } else {
      $name           = '';
      $short_desc     = '';
      $product_image  = '';      
      $product_catId  = '';
      $packageunitId         = '';
      $caseunitId         = '';
      $case_qty         = '';
      $caseqtyunitId         = '';
      $case_size         = '';
      $package_size         = '';
      $gst_percent         = '';
      $invoice_rate_per_unit         = '';
      $mrp_per_unit         = '';      
    }
    ?>
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body pt-3">
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
          </div>
          @endif
          <form method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
              <label for="name" class="col-md-2 col-lg-2 col-form-label">Name</label>
              <div class="col-md-10 col-lg-10">
                <input type="text" name="name" class="form-control" id="name" value="<?=$name?>" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="product_category" class="col-md-2 col-lg-2 col-form-label">Product Category</label>
              <div class="col-md-10 col-lg-8">                                                                                                              
              <select name="product_category" class="form-control" id="product_category" required>
                  <option value="" selected disabled>Select</option>
                  @if ($product_cat)
                      @foreach ($product_cat as $data)
                          <option value="{{ $data->id }}" @selected($data->id == $product_catId)>
                              {{ $data->category_name }}</option>
                      @endforeach
                  @endif
              </select>                           
              </div>
            </div>
            <div class="row mb-3">
              <label for="package_unit" class="col-md-2 col-lg-2 col-form-label">Package Unit</label>
              <div class="col-md-10 col-lg-8">                                                                                                              
              <select name="package_unit" class="form-control" id="package_unit" required>
                  <option value="" selected disabled>Select</option>
                  @if ($unit)
                      @foreach ($unit as $data)
                          <option value="{{ $data->id }}" @selected($data->id == $packageunitId)>
                              {{ $data->name }}</option>
                      @endforeach
                  @endif
              </select>                           
              </div>
            </div>
            <div class="row mb-3">
              <label for="size_id" class="col-md-2 col-lg-2 col-form-label">Package Size</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
              <input type="text" name="package_size" class="form-control" id="package_size" value="<?=$package_size?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="size_id" class="col-md-2 col-lg-2 col-form-label">Case Size</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
                <input type="text" name="case_size" class="form-control" id="case_size" value="<?=$case_size?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="case_unit" class="col-md-2 col-lg-2 col-form-label">Case Unit</label>
              <div class="col-md-10 col-lg-8">                                                                                                              
              <select name="case_unit" class="form-control" id="case_unit" required>
                  <option value="" selected disabled>Select</option>
                  @if ($unit)
                      @foreach ($unit as $data)
                          <option value="{{ $data->id }}" @selected($data->id == $caseunitId)>
                              {{ $data->name }}</option>
                      @endforeach
                  @endif
              </select>                           
              </div>
            </div>
            <div class="row mb-3">
              <label for="case_qty" class="col-md-2 col-lg-2 col-form-label">Per Case Qty</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
                <input type="text" name="case_qty" class="form-control" id="case_qty" value="<?=$case_qty?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="case_qty_unit" class="col-md-2 col-lg-2 col-form-label">Per Case Qty Unit</label>
              <div class="col-md-10 col-lg-8">                                                                                                              
              <select name="case_qty_unit" class="form-control" id="case_qty_unit" required>
                  <option value="" selected disabled>Select</option>
                  @if ($unit)
                      @foreach ($unit as $data)
                          <option value="{{ $data->id }}" @selected($data->id == $caseqtyunitId)>
                              {{ $data->name }}</option>
                      @endforeach
                  @endif
              </select>                           
              </div>
            </div>
            <div class="row mb-3">
              <label for="gst_percent" class="col-md-2 col-lg-2 col-form-label">GST Percent</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
                <!-- <input type="text" name="gst_percent" class="form-control" id="gst_percent" value="?=$gst_percent?>"> -->
                <select name="gst_percent" class="form-control" id="gst_percent" required>
                <option value="0" @selected($gst_percent == 0)>0%</option>
                <option value="5" @selected($gst_percent == 5)>5%</option>
                <option value="12" @selected($gst_percent == 12)>12%</option>
                <option value="18" @selected($gst_percent == 18)>18%</option>
                <option value="28" @selected($gst_percent == 28)>28%</option>                
              </select>  
              </div>
            </div>
            <div class="row mb-3">
              <label for="invoice_rate_per_unit" class="col-md-2 col-lg-2 col-form-label">Invoice Rate Per Unit</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
                <input type="text" name="invoice_rate_per_unit" class="form-control" id="invoice_rate_per_unit" value="<?=$invoice_rate_per_unit?>">
              </div>
            </div>
            <div class="row mb-3">
              <label for="mrp_per_unit" class="col-md-2 col-lg-2 col-form-label">Mrp Per Unit</label>
              <div class="col-md-10 col-lg-8">                                                                                                                            
                <input type="text" name="mrp_per_unit" class="form-control" id="mrp_per_unit" value="<?=$mrp_per_unit?>">
              </div>
            </div>
            <div class="row mb-3">
                <label for="short_description" class="col-md-2 col-lg-2 col-form-label">Short Description</label>
                <div class="col-md-10 col-lg-10">
                    <textarea name="short_desc" class="form-control"  rows="5" maxlength="75"><?= $short_desc ?></textarea>                    
                </div>
            </div>
            <div class="row mb-3">
              <label for="product_image" class="col-md-2 col-lg-2 col-form-label">Product Image</label>
              <div class="col-md-10 col-lg-10">
                <input type="file" name="product_image" class="form-control" id="product_image">
                <small class="text-info">* Only JPG, JPEG, ICO, SVG, PNG, WEBP files are allowed</small><br>
                <?php if($product_image != ''){?>
                  <img src="<?=env('UPLOADS_URL').'product/'.$product_image?>" class="img-thumbnail" alt="<?=$name?>" style="width: 150px; height: 150px; margin-top: 10px;">
                <?php } else {?>
                  <img src="<?=env('NO_IMAGE')?>" alt="<?=$name?>" class="img-thumbnail" style="width: 150px; height: 150px; margin-top: 10px;">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>