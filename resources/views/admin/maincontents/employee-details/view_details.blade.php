<?php
use App\Helpers\Helper;
use App\Models\Client;
use App\Models\Companies;
use App\Models\Employees;
use App\Models\EmployeeType;

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
          <ul class="nav nav-tabs nav-tabs-bordered">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1">Basic Info</button>
            </li>            
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2">Attendance</button>
            </li>            
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab3">Visit</button>
            </li>  
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab4">Odometer</button>
            </li>            
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab5">Orders</button>
            </li>                               
          </ul> 
          <div class="tab-content pt-2">     
            <div class="tab-pane fade show active" id="tab1">
              <!-- <p style="float:right;">
                ?php if($row->is_final_edit){?>
                  ?php if($row->is_published == 1){?>
                    <a href="?=url('admin/' . $controllerRoute . '/generate-nelp-form/'.Helper::encoded($row->id))?>" class="btn btn-outline-info btn-sm" title="Generate NELP Form & Shared"><i class="fa fa-pdf"> Generate NELP Form & Shared</i></a>
                  ?php }?>
                ?php }
                if ($row->is_import == 0) { 
                ?>
                <a href="?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"> Edit</i></a>
                ?php } ?>
              </p> -->
              <table class="table table-striped table-bordered nowrap">                
                <tbody>                
                    <tr>   
                    <?php if($admin->company_id == 0){ ?>
                    <th scope="col">Company Name</th>                    
                    <td>
                    <?php
                      $getCompany = Companies::select('id', 'name')->where('id', '=', $row->company_id)->first();
                      echo (($getCompany)?$getCompany->category_name:'');
                      ?>
                    </td>
                    <?php } ?>                                                                  
                    </tr>  
                    <tr>
                      <td>Employee Type</td>
                      <td><?php
                      $getRole = EmployeeType::select('id', 'name')->where('id', '=', $row->employee_type_id)->first();
                      echo (($getRole)?$getRole->name:'');
                      ?></td>
                    </tr>
                    <tr>
                      <td>Parent Employee</td>
                      <td><?php
                      $getParent = Employees::select('id', 'name')->where('id', '=', $row->parent_id)->first();
                      echo (($getParent)?$getParent->name:'');
                      ?></td>
                    </tr>
                    <tr>
                      <td>Employee No</td>
                      <td><?= $row->employee_no ?></td>
                    </tr>                    
                    <tr>
                      <td>Name</td>
                      <td><?= $row->name ?></td>
                    </tr>                
                    <tr>
                      <td>Email</td>
                      <td><?= $row->email ?></td>
                    </tr>
                    <tr>
                      <td>Alt Email</td>
                      <td><?= $row->alt_email ?></td>
                    </tr>
                    <tr>
                      <td>Phone</td>
                      <td><?=$row->phone?></td>
                    </tr>
                    <tr>
                      <td>Whatsapp No</td>
                      <td><?=$row->whatsapp_no?></td>
                    </tr>
                    <tr>
                      <td>Short Bio</td>
                      <td><?= $row->short_bio?></td>
                    </tr>
                    <tr>
                      <td>Address</td>
                      <td><?=$row->address?></td>
                    </tr>
                    <tr>
                      <td>Date of Birth</td>
                      <td><?= $row->dob ?></td>
                    </tr>
                    <tr>
                      <td>Date of Joining</td>
                      <td><?= $row->doj?></td>
                    </tr>
                    <tr>
                      <td>Qualification</td>
                      <td><?= $row->qualification ?></td>
                    </tr>
                    <tr>
                      <td>Profile Image</td>
                      <td><img src="<?=env('UPLOADS_URL').$row->profile_image?>" alt="<?=$row->name?>" style="width: 150px; height: 150px; margin-top: 10px;"></td>
                    </tr>                                        
                </tbody>
              </table>              
            </div>
            <div class="tab-pane fade pt-3" id="tab2">
                <h4>Attendance</h4>                               
            </div>
            <div class="tab-pane fade pt-3" id="tab3">            
                <h4>Visit</h4>                
            </div>
            <div class="tab-pane fade pt-3" id="tab4">            
                <h4>Odometer</h4>                
            </div>
            <div class="tab-pane fade pt-3" id="tab5">                             
                <table class="table table-striped table-bordered nowrap">                
                <tbody>                
                    <tr>   
                    <?php if($admin->company_id == 0){ ?>
                    <th scope="col">Company Name</th>                    
                    <td>
                    <?php
                      $getCompany = Companies::select('id', 'name')->where('id', '=', $row->company_id)->first();
                      echo (($getCompany)?$getCompany->category_name:'');
                      ?>
                    </td>
                    <?php } ?>                                                                  
                    </tr>  
                    <tr>
                      <td>Sl No</td>
                      <td><?=$order->sl_no?></td>
                    </tr>
                    <tr>
                      <td>Order No</td>
                      <td><?=$order->order_no?></td>
                    </tr>
                    <tr>
                      <td>Client</td>
                      <td>
                    <?php
                      $getClient = Client::select('id', 'name')->where('id', '=', $order->client)->first();
                      echo (($getClient)?$getClient->name:'');
                      ?>
                    </td>                    
                    </tr>                    
                    <tr>
                      <td>Employee</td>
                      <td>
                    <?php
                      $getEmployee = Employees::select('id', 'name')->where('id', '=', $order->employee)->first();
                      echo (($getEmployee)?$getEmployee->name:'');
                      ?>
                    </td>  
                    </tr>                
                    <tr>
                      <td>Order date/time</td>
                      <td><?= $order->order_date ?></td>
                    </tr>                                              
                </tbody>
              </table>              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>