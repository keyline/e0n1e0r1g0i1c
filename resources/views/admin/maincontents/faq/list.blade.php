<?php
use App\Models\FaqCategory;
use App\Helpers\Helper;
$controllerRoute = $module['controller_route'];
?>
<style type="text/css">

</style>
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
                  <th scope="col">FAQ Category</th>
                  <th scope="col">Question</th>
                  <th scope="col">Answer</th>
                  <th scope="col">Home Page</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody id="imageListId">
                <?php if(count($rows)>0){ $sl=1; foreach($rows as $row){?>
                  <tr class="listitemClass" id="card-<?=$row->id?>">
                    <th scope="row"><?=$sl++?></th>
                    <td>
                      <?php
                      $cat                 = FaqCategory::select('name')->where('id', '=', $row->faq_category_id)->first();
                      echo (($cat)?$cat->name:'');
                      ?>
                    </td>
                    <td><?=wordwrap($row->question,50,"<br>\n")?></td>
                    <td><?=wordwrap($row->answer,70,"<br>\n")?></td>
                    <td>
                      <?php if($row->is_home_page){?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-home-page-status/'.Helper::encoded($row->id))?>" class="badge bg-success" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i> YES</a>
                      <?php } else {?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-home-page-status/'.Helper::encoded($row->id))?>" class="badge bg-danger" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i> NO</a>
                      <?php }?>
                    </td>
                    <td>
                      <a href="<?=url('admin/' . $controllerRoute . '/edit/'.Helper::encoded($row->id))?>" class="btn btn-outline-primary btn-sm" title="Edit <?=$module['title']?>"><i class="fa fa-edit"></i></a>
                      <a href="<?=url('admin/' . $controllerRoute . '/delete/'.Helper::encoded($row->id))?>" class="btn btn-outline-danger btn-sm" title="Delete <?=$module['title']?>" onclick="return confirm('Do You Want To Delete This <?=$module['title']?>');"><i class="fa fa-trash"></i></a>
                      <?php if($row->status){?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-success btn-sm" title="Activate <?=$module['title']?>"><i class="fa fa-check"></i></a>
                      <?php } else {?>
                        <a href="<?=url('admin/' . $controllerRoute . '/change-status/'.Helper::encoded($row->id))?>" class="btn btn-outline-warning btn-sm" title="Deactivate <?=$module['title']?>"><i class="fa fa-times"></i></a>
                      <?php }?>
                    </td>
                  </tr>
                <?php } } else {?>
                  <tr>
                    <td colspan="6" style="text-align: center;color: red;">No Records Found !!!</td>
                  </tr>
                <?php }?>
              </tbody>
            </table>
            <input id="tableName" type="hidden" value="faqs" />
            <input id="primaryKey" type="hidden" value="id" />
          </div>
        </div>
      </div>
    </div>
  </div>
</section>