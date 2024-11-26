<?php
use App\Models\User;
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
          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">To</th>
                <th scope="col">Users</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Send</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if($rows){ $sl=1; foreach($rows as $key =>$value){?>
                <tr>
                  <td scope="row"><?=$sl++?></td>                         
                  <td>
                    <?php
                    $to_users   = $value->to_users;
                    if($to_users == 0){
                      echo 'All';
                    } elseif($to_users == 1){
                      echo 'Landlord';
                    } elseif($to_users == 2){
                      echo 'Tenant';
                    }
                    ?>
                  </td>
                  <td>
                    <div class="row">
                      <?php
                      $users = json_decode($value->users);
                      if(!empty($users)){ for($u=0;$u<count($users);$u++){
                        $user = User::select('first_name', 'last_name')->where('id', '=', $users[$u])->first();
                      ?>
                        <div class="col-md-4">
                          <span class="badge bg-primary"><?=(($user)?$user->first_name.' '.$user->last_name:'')?></span>
                        </div>
                      <?php } }?>
                    </div>
                  </td>
                  <td>{{ $value->title }}</td>
                  <td>{{ $value->description }}</td>
                  <td>
                      <?php if($value->is_send){?>
                          <span class="badge bg-success">YES</span>
                          <p><?=date_format(date_create($value->updated_at), "M d, Y h:i A")?></p>
                      <?php } else {?>
                          <span class="badge bg-danger">NO</span>
                      <?php }?>
                  </td>
                  <td>
                      <?php if(!$value->is_send){?>
                        <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($value->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                        <a href="<?=url('admin/' . $controllerRoute . '/send/'.Helper::encoded($value->id))?>" class="btn btn-outline-info btn-sm" title="Send" onclick="return confirm('Do you want to send this notifications ?');"><i class="fa fa-paper-plane"></i></a>
                        <?php if($value->status){?>
                          <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($value->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                        <?php } else {?>
                          <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($value->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                        <?php }?>
                      <?php }?>
                      <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($value->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              <?php } }?>
            </tbody>
          </table>
          <!-- End Table with stripped rows -->
        </div>
      </div>

    </div>
  </div>
</section>