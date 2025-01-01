<?php
use App\Models\District;
use App\Models\Employees;
use App\Models\EmployeeType;
use App\Helpers\Helper;
?>
<style>
  .tree {
      display: flex;
      align-items: center;
      flex-direction: column;
  }
  .tree ul {
      padding-top: 20px;
      display: flex;
      justify-content: center;
      list-style-type: none;
      display: none; /* Initially collapsed */
  }
  .tree ul.visible {
      display: flex; /* Show when expanded */
  }
  .tree li {
      text-align: center;
      position: relative;
      margin: 0 20px;
  }
  .tree li::before, .tree li::after {
      content: '';
      position: absolute;
      top: 0;
      border-top: 1px solid #ccc;
      width: 20px;
      height: 20px;
  }
  .tree li::before {
      left: -20px;
      border-left: 1px solid #ccc;
  }
  .tree li::after {
      right: -20px;
      border-right: 1px solid #ccc;
  }
  .tree li:first-child::before {
      display: none;
  }
  .tree li:last-child::after {
      display: none;
  }
  .tree li div {
      border: 1px solid #ccc;
      padding: 10px 15px;
      text-align: center;
      border-radius: 5px;
      background: white;
      cursor: pointer;
  }
  .tree li div:hover {
      background: #e6e6e6;
      color: #000;
  }
  .toggle::before {
      content: "+ ";
      font-weight: bold;
      color: green;
  }
  .toggle.expanded::before {
      content: "- ";
      color: red;
  }
  .tree li ul {
      margin-top: 40px;
  }
  .tree li ul::before {
      content: '';
      position: absolute;
      top: -20px;
      left: 50%;
      border-left: 1px solid #ccc;
      height: 20px;
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
            Helper::pr($districtIds,0);
            $districtArray = sort($districtIds);
            Helper::pr($districtArray);
            ?>
            <div class="tree">
              <ul class="visible">
                  <li>
                      <div class="toggle">WEST BENGAL</div>
                      <ul>
                          <li>
                              <div class="toggle">VP Marketing 1</div>
                              <ul>
                                  <li>
                                      <div class="toggle">Marketing Manager</div>
                                      <ul>
                                          <li>
                                              <div class="toggle">Team Lead</div>
                                              <ul>
                                                  <li>
                                                      <div class="toggle">Senior Executive</div>
                                                      <ul>
                                                          <li>
                                                              <div class="toggle">Executive</div>
                                                              <ul>
                                                                  <li>
                                                                      <div class="toggle">Intern</div>
                                                                      <ul>
                                                                          <li>
                                                                              <div class="toggle">Trainee</div>
                                                                              <ul>
                                                                                  <li>
                                                                                      <div class="toggle">Trainee 2</div>
                                                                                  </li>
                                                                              </ul>
                                                                          </li>
                                                                      </ul>
                                                                  </li>
                                                              </ul>
                                                          </li>
                                                      </ul>
                                                  </li>
                                              </ul>
                                          </li>
                                      </ul>
                                  </li>
                              </ul>
                          </li>
                          
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
  // Select all toggle elements
  const toggles = document.querySelectorAll('.toggle');

  // Add click event to each toggle
  toggles.forEach(toggle => {
      toggle.addEventListener('click', function (event) {
          event.stopPropagation(); // Prevent triggering parent elements
          const childUl = this.parentNode.querySelector('ul');
          if (childUl) {
              childUl.classList.toggle('visible');
              this.classList.toggle('expanded');
          }
      });
  });
</script>