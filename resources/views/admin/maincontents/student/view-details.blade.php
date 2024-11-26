<?php
use App\Models\Center;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\DocumentType;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\Source;
use App\Models\Label;
use App\Models\StudentLabelMark;
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
          <h5 class="card-title">
            <a href="<?=url('admin/' . $controllerRoute . '/list/')?>" class="btn btn-outline-success btn-sm">Back</a>
          </h5>
          <div class="row">
            <div class="col-md-6 mb-3">
              <h5>Student No.</h5>
              <span><?=(($student)?$student->student_no:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Center</h5>
              <span>
                <?php
                $centerRow = Center::select('name')->where('id', '=', (($student)?$student->center_id:''))->first();
                echo (($centerRow)?$centerRow->name:'');
                ?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Name</h5>
              <span><?=(($student)?$student->name:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Address</h5>
              <span><?=(($student)?$student->address:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Country</h5>
              <span>
                <?php
                $countryRow = Country::select('name')->where('id', '=', (($student)?$student->country_id:''))->first();
                echo (($countryRow)?$countryRow->name:'');
                ?>
              </span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>State</h5>
              <span>
                <?php
                $stateRow = State::select('name')->where('id', '=', (($student)?$student->state_id:''))->first();
                echo (($stateRow)?$stateRow->name:'');
                ?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>District</h5>
              <span>
                <?php
                $districtRow = District::select('name')->where('id', '=', (($student)?$student->district_id:''))->first();
                echo (($districtRow)?$districtRow->name:'');
                ?>
              </span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Locality</h5>
              <span><?=(($student)?$student->locality:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Pincode</h5>
              <span><?=(($student)?$student->pincode:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Landmark</h5>
              <span><?=(($student)?$student->landmark:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Email</h5>
              <span><?=(($student)?$student->email:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Phone No.</h5>
              <span><?=(($student)?$student->phone:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Alt No.</h5>
              <span><?=(($student)?$student->alt_phone:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Whatsap No.</h5>
              <span><?=(($student)?$student->whatsapp_no:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>DOB</h5>
              <span><?=(($student)?date_format(date_create($student->dob), "M d, Y"):'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Source</h5>
              <span>
                <?php
                $sourceRow = Source::select('name')->where('id', '=', (($student)?$student->source_id:''))->first();
                echo (($sourceRow)?$sourceRow->name:'');
                ?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Guardian Name</h5>
              <span><?=(($student)?$student->guardian_name:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Guardian Relation</h5>
              <span><?=(($student)?$student->guardian_relation:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Student Document Type</h5>
              <span>
                <?php
                $docType = DocumentType::select('name')->where('id', '=', (($student)?$student->student_doc_type_id:''))->first();
                echo (($docType)?$docType->name:'');
                ?>
              </span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Student ID Proof</h5>
              <span>
                <?php if($student){ if($student->student_id_proof != ''){?>
                  <a href="<?=env('UPLOADS_URL').'student/'.$student->student_id_proof?>" target="_blank" class="badge bg-primary" download>View File</a>
                <?php } }?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Guardian Document Type</h5>
              <span>
                <?php
                $docType = DocumentType::select('name')->where('id', '=', (($student)?$student->guardian_doc_type_id:''))->first();
                echo (($docType)?$docType->name:'');
                ?>
              </span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Guardian ID Proof</h5>
              <span>
                <?php if($student){ if($student->guardian_id_proof != ''){?>
                  <a href="<?=env('UPLOADS_URL').'student/'.$student->guardian_id_proof?>" target="_blank" class="badge bg-primary" download>View File</a>
                <?php } }?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Student Photo</h5>
              <span>
                <?php if($student){ if($student->student_photo != ''){?>
                  <img src="<?=env('UPLOADS_URL').'student/'.$student->student_photo?>" style="width: 150px; height: 150px; margin-top: 10px; border-radius: 50%;">
                <?php } }?>
              </span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Current Level</h5>
              <span>
                <?php
                $labelRow = Label::select('name')->where('id', '=', (($student)?$student->current_label_id:''))->first();
                echo (($labelRow)?$labelRow->name:'');
                ?>
              </span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Current Level No.</h5>
              <span><?=(($student)?$student->current_label_marks:'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Created By</h5>
              <span><?=(($student)?$student->created_by:'')?></span>
            </div>

            <div class="col-md-6 mb-3">
              <h5>Created At</h5>
              <span><?=(($student)?date_format(date_create($student->created_at), "M d, Y h:i A"):'')?></span>
            </div>
            <div class="col-md-6 mb-3">
              <h5>Updated At</h5>
              <span><?=(($student)?date_format(date_create($student->updated_at), "M d, Y h:i A"):'')?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>