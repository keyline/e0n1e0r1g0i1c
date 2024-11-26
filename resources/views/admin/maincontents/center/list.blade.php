<?php
use App\Models\CenterType;
use App\Models\CenterTimeSlot;
use App\Models\FranchiseOwner;
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
            <a href="<?=url('admin/' . $controllerRoute . '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=$module['title']?></a>
          </h5>
          <div class="dt-responsive table-responsive">
            <table id="simpletable" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Center No<br>Center Type</th>
                  <th scope="col">Center Owner</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email<br>Phone<br>Whatsapp No.</th>
                  <th scope="col">Address</th>
                  <th scope="col">Created By<br>Updated By</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                  <tr>
                    <th scope="row"><?=$sl++?></th>
                    <td>
                      <?=$row->center_no?><br>
                      <?php
                      $center_type                 = CenterType::select('name')->where('id', '=', $row->center_type_id)->first();
                      echo (($center_type)?'<span class="badge bg-primary">'.$center_type->name.'</span>':'');
                      ?>
                    </td>
                    <td>
                      <?php
                      $center_owner                 = FranchiseOwner::select('name')->where('id', '=', $row->center_owner_id)->first();
                      echo (($center_owner)?$center_owner->name:'Kidszone Education');
                      ?>
                    </td>
                    <td><?=$row->name?></td>
                    <td><?=$row->email?><br><?=$row->phone?><br><?=$row->whatsapp_no?></td>
                    <td><?=$row->address?></td>
                    <td>
                      <?php
                      echo $row->created_by.'<br>';
                      echo date_format(date_create($row->created_at), "M d, Y h:i A").'<br>';
                      echo '<hr>';
                      if($row->updated_by != ''){
                        echo $row->updated_by.'<br>';
                        echo date_format(date_create($row->updated_at), "M d, Y h:i A");
                      }
                      ?>
                    </td>
                    <td>
                      <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                      <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                      <?php if($row->status){?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                      <?php } else {?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                      <?php }?>
                      <br><br>
                      <?php
                      $timeSlotCount = CenterTimeSlot::where('center_id', '=', $row->id)->count();
                      ?>
                      <a href="<?=url('admin/' . $controllerRoute . '/slot-time/'.Helper::encoded($row->id))?>" class="btn btn-info btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-clock"></i> Slot Times (<?=$timeSlotCount?>)</a>
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