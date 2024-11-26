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
            <h6 class="card-subtitle">Total Own Centers</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-12">
                <h2 class="card-title text-inherit"><?=$center_count?></h2>
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
            <h6 class="card-subtitle">Total Franchises</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit"><?=$franchise_count?></h2>
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
            <h6 class="card-subtitle">Total Teachers</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit"><?=$teacher_count?></h2>
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
            <h6 class="card-subtitle">Total Students</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit"><?=$student_count?></h2>
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
            <h6 class="card-subtitle">Total Notices</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit"><?=$notice_count?></h2>
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
            <h6 class="card-subtitle">Total Enquires</h6>
            <div class="row align-items-center gx-2 mb-1">
              <div class="col-6">
                <h2 class="card-title text-inherit"><?=$enquiry_count?></h2>
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
  </div>
<!-- End Content -->