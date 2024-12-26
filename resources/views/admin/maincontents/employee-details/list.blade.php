<?php
use App\Helpers\Helper;
use App\Models\Employees;
use App\Models\EmployeeType;
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
          <?php if($slug != 'all'){?>
            <h5 class="card-title">
              <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/add/')?>" class="btn btn-outline-success btn-sm">Add <?=ucfirst($slug)?></a>
            </h5>
          <?php }?>
          <div class="dt-responsive table-responsive">
            <table id="<?=((count($rows)>0)?'simpletable':'')?>" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Employee No</th>
                  <?php if($slug != 'regional-head'){ ?>
                    <th scope="col">Parent Employee Name </th>
                  <?php } ?>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <!-- <th scope="col">Date of Birth</th>
                  <th scope="col">Address</th> -->
                  <th scope="col">Mobile</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                  <tr>
                    <th scope="row"><?=$sl++?></th>
                    <td>
                      <?=$row->employee_no?>
                      <?php if($slug == 'all'){?>
                        <br>
                        <span class="badge bg-success" style="font-size: 9px;"><?=$row->employee_type_name?></span>
                      <?php }?>
                    </td>
                    <?php if($slug != 'regional-head'){ ?>
                      <td><?php
                        $getParent = Employees::select('id', 'name')->where('id', '=', $row->parent_id)->first();
                        echo (($getParent)?$getParent->name:'');
                        ?>
                      </td>
                    <?php } ?>
                    <td><?=$row->name?></td>
                    <td><?=$row->email?></td>
                    <!-- <td><?=$row->dob?></td>
                    <td><?= $row->address?></td> -->
                    <td><?=$row->phone?></td>
                    <td>
                      <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                      <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                      <?php if($row->status){?>
                        <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                      <?php } else {?>
                        <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                      <?php }?>
                      <a href="<?=url('admin/' . $controllerRoute .'/'.$slug. '/view_details/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="ViewDetails <?=$module['title']?>" target="_blank"><i class="fa fa-eye"></i></a>
                    </td>
                  </tr>
                <?php } } else {?>
                  <tr>
                    <td colspan="6" style="text-align: center;color: red;">No Records Found !!!</td>
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