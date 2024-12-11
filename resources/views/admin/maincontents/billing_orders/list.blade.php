<?php
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\Client;
use App\Models\ClientType;
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
          <h5 class="card-title">
            <a href="<?=url('admin/' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5>
          <div class="dt-responsive table-responsive">
            <table id="simpletable" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Order No</th>
                  <th scope="col">Employee Type</th>
                  <th scope="col">Employee Name</th>
                  <th scope="col">Client Type</th>
                  <th scope="col">Client Name</th>
                  <th scope="col">Address</th>
                  <th scope="col">Order Date</th>
                  <th scope="col">Net Total</th>                  
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                  <tr>
                    <th scope="row"><?=$sl++?></th>
                    <td><?=$row->order_no?></td>
                    <td>
                      <?php
                      $getEmployeeType = EmployeeType::select('name')->where('id', '=', $row->employee_type_id)->first();
                      echo (($getEmployeeType)?$getEmployeeType->name:'');
                      ?>
                    </td>
                    <td>
                      <?php
                      $getEmployee = Employees::select('name')->where('id', '=', $row->employee_id)->first();
                      echo (($getEmployee)?$getEmployee->name:'');
                      ?>
                    </td> 
                    <td>
                      <?php
                      $getEmployeeType = ClientType::select('name')->where('id', '=', $row->client_type_id)->first();
                      echo (($getEmployeeType)?$getEmployeeType->name:'');
                      ?>
                    </td>
                    <td>
                      <?php
                      $getEmployee = Client::select('name')->where('id', '=', $row->client_id)->first();
                      echo (($getEmployee)?$getEmployee->name:'');
                      ?>
                    </td>                                                    
                    <td><?php
                      $getClient = Client::select('address')->where('id', '=', $row->client_id)->first();
                      echo (($getClient)?$getClient->address:'');
                      ?></td>
                    <td><?=date('M d Y h:i A', strtotime($row->order_timestamp))?></td>
                    <td><?=$row->net_total?></td>                    
                    <td>    
                      <a href="<?=url('admin/' . $controllerRoute . '/view_order_details/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="ViewDetails <?=$module['title']?>" target="_blank"><i class="fa fa-eye"></i></a>                                                           
                    </td>
                  </tr>
                <?php } } else {?>
                  <tr>
                    <td colspan="12" style="text-align: center;color: red;">No Records Found !!!</td>
                  </tr>
                <?php }?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>