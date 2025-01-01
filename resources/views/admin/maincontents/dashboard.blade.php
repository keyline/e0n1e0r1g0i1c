<?php
use App\Models\District;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Helpers\Helper;
?>
<style>
  .tree ul {
      list-style: none;
      margin: 0;
      padding-left: 20px;
      position: relative;
      display: none; /* Initially hide all child nodes */
  }

  .tree li {
      margin: 0;
      padding: 0 0 10px 20px;
      line-height: 1.5em;
      position: relative;
  }

  .tree li::before, .tree li::after {
      content: '';
      position: absolute;
      left: -10px;
  }

  .tree li::before {
      border-left: 2px solid #ccc;
      top: 0;
      bottom: 50%;
      height: 100%;
      width: 10px;
  }

  .tree li::after {
      border-top: 2px solid #ccc;
      top: 1.5em;
      width: 10px;
      height: 0;
  }

  .tree li:last-child::before {
      height: 50%;
  }

  .node {
      display: inline-block;
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 5px 10px;
      background: #f9f9f9;
      cursor: pointer;
  }

  .node:hover {
      background: #e0e0e0;
  }
</style>
<!-- Content -->
  <div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
      <div class="row align-items-center">
        <div class="col">
          <h1 class="page-header-title"><?=$page_header?></h1>
        </div>
        <!-- End Col -->
        <!-- <div class="col-auto">
          <a class="btn btn-primary" href="javascript:;" data-bs-toggle="modal" data-bs-target="#inviteUserModal">
            <i class="bi-person-plus-fill me-1"></i> Invite users
          </a>
        </div> -->
        <!-- End Col -->
      </div>
      <!-- End Row -->
    </div>
    <!-- End Page Header -->
    <!-- Stats -->
    <div class="row">
      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/own-center/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-12">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-success text-success">
              <i class="bi-graph-up"></i> 12.5%
            </span>
            <span class="text-body fs-6 ms-1">from 70,104</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>
      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/franchise-center/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-success text-success">
              <i class="bi-graph-up"></i> 1.7%
            </span>
            <span class="text-body fs-6 ms-1">from 29.1%</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>
      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/teacher/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-danger text-danger">
              <i class="bi-graph-down"></i> 4.4%
            </span>
            <span class="text-body fs-6 ms-1">from 61.2%</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>
      <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/student/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-secondary text-body">0.0%</span>
            <span class="text-body fs-6 ms-1">from 2,913</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>

      <div class="col-sm-6 col-lg-6 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/notice/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-secondary text-body">0.0%</span>
            <span class="text-body fs-6 ms-1">from 2,913</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>
      <div class="col-sm-6 col-lg-6 mb-3 mb-lg-5">
        <!-- Card -->
        <a class="card card-hover-shadow h-100" href="<?=url('admin/enquiry/list')?>">
          <div class="card-body">
            <h6 class="card-subtitle">Test</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit">0</h2>
              </div>
            </div>
            <!-- End Row -->
            <!-- <span class="badge bg-soft-secondary text-body">0.0%</span>
            <span class="text-body fs-6 ms-1">from 2,913</span> -->
          </div>
        </a>
        <!-- End Card -->
      </div>
    </div>
    <!-- End Stats -->
    <div class="row">
      <div class="col-sm-12 col-lg-12 mb-3 mb-lg-5">
        <div class="card">
          <div class="card-header"><h5>Employee Tree</h5></div>
          <div class="card-body">
            <?php
            $districtIds = [];
            $emps = Employees::select('assign_district')->where('status', '!=', 3)->get();
            if($emps){
              foreach($emps as $emp){
                $assign_districts = json_decode($emp->assign_district);
                if(!empty($assign_districts)){
                  for($d=0;$d<count($assign_districts);$d++){
                    if(!in_array($assign_districts[$d], $districtIds)){
                      $districtIds[] = $assign_districts[$d];
                    }
                  }
                }
              }
            }
            ?>
            <div id="tree-view">
              <ul class="tree">
                  <li>
                      <span class="node">WEST BENGAL</span>
                      <ul>
                        <?php
                        $level1_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 1)->first();
                        $level2_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 2)->first();
                        $level3_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 3)->first();
                        $level4_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 4)->first();
                        $level5_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 5)->first();
                        $level6_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 6)->first();
                        $level7_emp_type = EmployeeType::select('prefix')->where('status', '=', 1)->where('level', '=', 7)->first();
                        if(!empty($districtIds)){ for($d=0;$d<count($districtIds);$d++){
                          $getDistrict = District::select('id', 'name')->where('id', '=', $districtIds[$d])->first();
                        ?>
                          <li>
                              <span class="node"><?=(($getDistrict)?$getDistrict->name:'')?></span>
                              <ul>
                                <?php
                                $getEmps1 = Employees::select('name')->where('employee_type_id', '=', 1)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                if($getEmps1){ foreach($getEmps1 as $getEmp1){
                                ?>
                                  <li>
                                    <span class="node"><?=(($getEmp1)?$getEmp1->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level1_emp_type)?$level1_emp_type->prefix:'')?>)</span>
                                    <ul>
                                      <?php
                                      $getEmps2 = Employees::select('name')->where('employee_type_id', '=', 2)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                      if($getEmps2){ foreach($getEmps2 as $getEmp2){
                                      ?>
                                        <li>
                                            <span class="node"><?=(($getEmp2)?$getEmp2->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level2_emp_type)?$level2_emp_type->prefix:'')?>)</span>
                                            <ul>
                                              <?php
                                              $getEmps3 = Employees::select('name')->where('employee_type_id', '=', 3)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                              if($getEmps3){ foreach($getEmps3 as $getEmp3){
                                              ?>
                                                <li>
                                                    <span class="node"><?=(($getEmp3)?$getEmp3->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level3_emp_type)?$level3_emp_type->prefix:'')?>)</span>
                                                    <ul>
                                                      <?php
                                                      $getEmps4 = Employees::select('name')->where('employee_type_id', '=', 4)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                      if($getEmps4){ foreach($getEmps4 as $getEmp4){
                                                      ?>
                                                        <li>
                                                            <span class="node"><?=(($getEmp4)?$getEmp4->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level4_emp_type)?$level4_emp_type->prefix:'')?>)</span>
                                                            <ul>
                                                              <?php
                                                              $getEmps5 = Employees::select('name')->where('employee_type_id', '=', 5)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                              if($getEmps5){ foreach($getEmps5 as $getEmp5){
                                                              ?>
                                                                <li>
                                                                    <span class="node"><?=(($getEmp5)?$getEmp5->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level5_emp_type)?$level5_emp_type->prefix:'')?>)</span>
                                                                    <ul>
                                                                      <?php
                                                                      $getEmps6 = Employees::select('name')->where('employee_type_id', '=', 6)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                                      if($getEmps6){ foreach($getEmps6 as $getEmp6){
                                                                      ?>
                                                                        <li>
                                                                            <span class="node"><?=(($getEmp6)?$getEmp6->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level6_emp_type)?$level6_emp_type->prefix:'')?>)</span>
                                                                            <ul>
                                                                              <?php
                                                                              $getEmps7 = Employees::select('name')->where('employee_type_id', '=', 7)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                                              if($getEmps7){ foreach($getEmps7 as $getEmp7){
                                                                              ?>
                                                                                <li>
                                                                                    <span class="node"><?=(($getEmp7)?$getEmp7->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level7_emp_type)?$level7_emp_type->prefix:'')?>)</span>
                                                                                </li>
                                                                              <?php } }?> 
                                                                            </ul>
                                                                        </li>
                                                                      <?php } }?>
                                                                    </ul>
                                                                </li>
                                                              <?php } }?>
                                                            </ul>
                                                        </li>
                                                      <?php } }?>
                                                    </ul>
                                                </li>
                                              <?php } }?>
                                            </ul>
                                        </li>
                                      <?php } } else {?>
                                        <li>
                                            <span class="node"><span style="color:red;">-NIL-</span> (<?=(($level2_emp_type)?$level2_emp_type->prefix:'')?>)</span>
                                            <ul>
                                              <?php
                                              $getEmps3 = Employees::select('name')->where('employee_type_id', '=', 3)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                              if($getEmps3){ foreach($getEmps3 as $getEmp3){
                                              ?>
                                                <li>
                                                    <span class="node"><?=(($getEmp3)?$getEmp3->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level3_emp_type)?$level3_emp_type->prefix:'')?>)</span>
                                                    <ul>
                                                      <?php
                                                      $getEmps4 = Employees::select('name')->where('employee_type_id', '=', 4)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                      if($getEmps4){ foreach($getEmps4 as $getEmp4){
                                                      ?>
                                                        <li>
                                                            <span class="node"><?=(($getEmp4)?$getEmp4->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level4_emp_type)?$level4_emp_type->prefix:'')?>)</span>
                                                            <ul>
                                                              <?php
                                                              $getEmps5 = Employees::select('name')->where('employee_type_id', '=', 5)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                              if($getEmps5){ foreach($getEmps5 as $getEmp5){
                                                              ?>
                                                                <li>
                                                                    <span class="node"><?=(($getEmp5)?$getEmp5->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level5_emp_type)?$level5_emp_type->prefix:'')?>)</span>
                                                                    <ul>
                                                                      <?php
                                                                      $getEmps6 = Employees::select('name')->where('employee_type_id', '=', 6)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                                      if($getEmps6){ foreach($getEmps6 as $getEmp6){
                                                                      ?>
                                                                        <li>
                                                                            <span class="node"><?=(($getEmp6)?$getEmp6->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level6_emp_type)?$level6_emp_type->prefix:'')?>)</span>
                                                                            <ul>
                                                                              <?php
                                                                              $getEmps7 = Employees::select('name')->where('employee_type_id', '=', 7)->where('status', '=', 1)->where('assign_district', 'LIKE', '%'.$districtIds[$d].'%')->get();
                                                                              if($getEmps7){ foreach($getEmps7 as $getEmp7){
                                                                              ?>
                                                                                <li>
                                                                                    <span class="node"><?=(($getEmp7)?$getEmp7->name:'<span style="color:red;">-NIL-</span>')?> (<?=(($level7_emp_type)?$level7_emp_type->prefix:'')?>)</span>
                                                                                </li>
                                                                              <?php } }?> 
                                                                            </ul>
                                                                        </li>
                                                                      <?php } }?>
                                                                    </ul>
                                                                </li>
                                                              <?php } }?>
                                                            </ul>
                                                        </li>
                                                      <?php } }?>
                                                    </ul>
                                                </li>
                                              <?php } }?>
                                            </ul>
                                        </li>
                                      <?php }?>
                                    </ul>
                                  </li>
                                <?php } }?>
                              </ul>
                          </li>
                        <?php } }?>
                      </ul>
                  </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- End Content -->
<script>
  document.querySelectorAll('.node').forEach(node => {
      node.addEventListener('click', function (e) {
          e.stopPropagation(); // Prevent event from bubbling up

          const parentLi = this.parentElement;
          const childUl = parentLi.querySelector('ul');

          if (childUl) {
              // Toggle visibility
              if (childUl.style.display === 'none' || childUl.style.display === '') {
                  childUl.style.display = 'block'; // Expand
              } else {
                  childUl.style.display = 'none'; // Collapse
              }
          }
      });
  });
</script>