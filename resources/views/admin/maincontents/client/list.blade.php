<?php
use App\Helpers\Helper;
use App\Models\ClientType;
use App\Models\Admin;
use App\Models\District;
use App\Models\Role;

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
            <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=ucfirst($slug)?></a>
          </h5>
          <div class="dt-responsive table-responsive">
            <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Client No.</th>
                  <!-- <th scope="col">Client Type</th> -->
                  <th scope="col">Name</th>                  
                  <th scope="col">Phone</th>
                  <th scope="col">Address</th>
                  <th scope="col">District</th>
                  <th scope="col">Created Info<hr>Updated Info</th> 
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                  <tr>
                    <th scope="row"><?=$sl++?></th>
                    <td><?=$row->client_no?></td>
                    <!-- <td>
                      <?php
                      $getClientType = ClientType::select('name')->where('id', '=', $row->client_type_id)->first();
                      echo (($getClientType)?$getClientType->name:'');
                      ?>
                    </td> -->
                    <td><?=$row->name?></td>                    
                    <td><?=$row->phone?></td>
                    <td><?=wordwrap($row->address,25,"<br>\n")?></td>
                    <td>
                      <?php
                      $getDistrict = District::select('name')->where('id', '=', $row->district_id)->first();
                      echo (($getDistrict)?$getDistrict->name:'');
                      ?>
                    </td>
                    <td><?php
                      $getCreateUser = Admin::select('id', 'name')->where('id', '=', $row->created_by)->first();
                      $getUpdateUser = Admin::select('id', 'name')->where('id', '=', $row->updated_by)->first();                      
                      ?>
                      <?=(($getCreateUser)?$getCreateUser->name:'')?><br><?=$row->created_at?><hr><?=(($getUpdateUser)?$getUpdateUser->name:'')?><br><?=$row->updated_at?>
                    </td>  
                    <td>
                      <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=ucfirst($slug)?>"><i class="fa fa-edit"></i></a>
                      <!-- <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=ucfirst($slug)?>" onclick="return confirm('Do You Want To Delete This <?=ucfirst($slug)?>');"><i class="fa fa-trash"></i></a> -->
                      <?php if($row->status){?>
                        <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=ucfirst($slug)?>"><i class="fa fa-check"></i></a>
                      <?php } else {?>
                        <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=ucfirst($slug)?>"><i class="fa fa-times"></i></a>
                      <?php }?>
                      <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/view_details/'.Helper::encoded($row->id))?>" class="btn btn-outline-info btn-sm" title="ViewDetails <?=$module['title']?>" target="_blank"><i class="fa fa-info-circle"></i></a>
                    </td>
                  </tr>
                <?php } } else {?>
                  <tr>
                    <td colspan="9" style="text-align: center;color: red;">No Records Found !!!</td>
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