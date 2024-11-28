<?php
use Illuminate\Support\Facades\Route;;
$routeName    = Route::current();
$pageName     = explode("/", $routeName->uri());
$pageSegment  = $pageName[1];
$pageFunction = ((count($pageName)>2)?$pageName[2]:'');
// dd($routeName);
if(!empty($parameters)){
  if (array_key_exists("id1",$parameters)){
    $pId1 = Helper::decoded($parameters['id1']);
  } else {
    $pId1 = Helper::decoded($parameters['id']);
  }
  if(count($parameters) > 1){
    $pId2 = Helper::decoded($parameters['id2']);
  }
}
?>
<div class="navbar-vertical-container">
  <div class="navbar-vertical-footer-offset">
    <!-- Logo -->
    <a class="navbar-brand" href="<?=url('admin/dashboard')?>" aria-label="Front">
      <img class="navbar-brand-logo" src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>" alt="<?=$generalSetting->site_name?>" data-hs-theme-appearance="default" style="margin: 0 auto;">
      <img class="navbar-brand-logo" src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>" alt="<?=$generalSetting->site_name?>" data-hs-theme-appearance="dark" style="margin: 0 auto;">
      <img class="navbar-brand-logo-mini" src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>" alt="<?=$generalSetting->site_name?>" data-hs-theme-appearance="default" style="margin: 0 auto;">
      <img class="navbar-brand-logo-mini" src="<?=env('UPLOADS_URL').$generalSetting->site_logo?>" alt="<?=$generalSetting->site_name?>" data-hs-theme-appearance="dark" style="margin: 0 auto;">
    </a>
    <!-- End Logo -->
    <!-- Navbar Vertical Toggle -->
    <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
      <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
      <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
    </button>
    <!-- End Navbar Vertical Toggle -->
    <!-- Content -->
    <div class="navbar-vertical-content">
      <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
        <!-- dashboard -->
          <div class="nav-item">
            <a class="nav-link <?=(($pageSegment == 'dashboard')?'active':'')?>" href="<?=url('admin/dashboard')?>" data-placement="left">
              <i class="fa fa-home nav-icon"></i>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </div>
        <!-- End dashboard -->
        <!-- masters -->
          <div class="nav-item">
            <a class="nav-link dropdown-toggle active <?=(($pageSegment == 'document-type' || $pageSegment == 'user-type' || $pageSegment == 'center-type' || $pageSegment == 'product-type' || $pageSegment == 'product' || $pageSegment == 'client-type' || $pageSegment == 'religion' || $pageSegment == 'caste-category' || $pageSegment == 'country' || $pageSegment == 'state' || $pageSegment == 'district' || $pageSegment == 'source' || $pageSegment == 'session-year' || $pageSegment == 'label')?'':'collapsed')?>" href="#navbarVerticalMenuMasters" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMasters" aria-expanded="<?=(($pageSegment == 'document-type' || $pageSegment == 'user-type' || $pageSegment == 'center-type' || $pageSegment == 'religion' || $pageSegment == 'caste-category' || $pageSegment == 'country' || $pageSegment == 'state' || $pageSegment == 'district' || $pageSegment == 'source' || $pageSegment == 'session-year' || $pageSegment == 'label')?'true':'false')?>" aria-controls="navbarVerticalMenuMasters">
              <i class="fa fa-database nav-icon"></i>
              <span class="nav-link-title">Masters</span>
            </a>
            <div id="navbarVerticalMenuMasters" class="nav-collapse collapse <?=(($pageSegment == 'document-type' || $pageSegment == 'user-type' || $pageSegment == 'center-type' || $pageSegment == 'product-type' || $pageSegment == 'product' || $pageSegment == 'client-type' || $pageSegment == 'religion' || $pageSegment == 'caste-category' || $pageSegment == 'country' || $pageSegment == 'state' || $pageSegment == 'district' || $pageSegment == 'source' || $pageSegment == 'session-year' || $pageSegment == 'label')?'show':'')?>" data-bs-parent="#navbarVerticalMenu">
              <a class="nav-link <?=(($pageSegment == 'document-type')?'active':'')?>" href="<?=url('admin/document-type/list')?>">Document Types</a>
              <a class="nav-link <?=(($pageSegment == 'user-type')?'active':'')?>" href="<?=url('admin/user-type/list')?>">User Types</a>
              <a class="nav-link <?=(($pageSegment == 'product-type')?'active':'')?>" href="<?=url('admin/product-type/list')?>">Product Types</a>
              <a class="nav-link <?=(($pageSegment == 'product')?'active':'')?>" href="<?=url('admin/product/list')?>">Product</a>
              <a class="nav-link <?=(($pageSegment == 'center-type')?'active':'')?>" href="<?=url('admin/center-type/list')?>">Center Types</a>
              <a class="nav-link <?=(($pageSegment == 'client-type')?'active':'')?>" href="<?=url('admin/client-type/list')?>">Client Types</a>
              <a class="nav-link <?=(($pageSegment == 'religion')?'active':'')?>" href="<?=url('admin/religion/list')?>">Religions</a>
              <a class="nav-link <?=(($pageSegment == 'caste-category')?'active':'')?>" href="<?=url('admin/caste-category/list')?>">Caste Categories</a>
              <a class="nav-link <?=(($pageSegment == 'country')?'active':'')?>" href="<?=url('admin/country/list')?>">Countries</a>
              <a class="nav-link <?=(($pageSegment == 'state')?'active':'')?>" href="<?=url('admin/state/list')?>">States</a>
              <a class="nav-link <?=(($pageSegment == 'district')?'active':'')?>" href="<?=url('admin/district/list')?>">Districts</a>
              <a class="nav-link <?=(($pageSegment == 'source')?'active':'')?>" href="<?=url('admin/source/list')?>">Sources</a>
              <a class="nav-link <?=(($pageSegment == 'session-year')?'active':'')?>" href="<?=url('admin/session-year/list')?>">Session Years</a>
              <!-- <a class="nav-link <?=(($pageSegment == 'label')?'active':'')?>" href="<?=url('admin/label/list')?>">Labels</a> -->
            </div>
          </div>
        <!-- End masters -->
         <!-- Access & Permission -->
         <div class="nav-item">
            <a class="nav-link dropdown-toggle active <?=(($pageSegment == 'modules' || $pageSegment == 'role' || $pageSegment == 'sub-user')?'':'collapsed')?>" href="#navbarVerticalMenuAccess" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAccess" aria-expanded="<?=(($pageSegment == 'modules' || $pageSegment == 'role' || $pageSegment == 'sub-user')?'true':'false')?>" aria-controls="navbarVerticalMenuAccess">
              <i class="fa fa-database nav-icon"></i>
              <span class="nav-link-title">Access & Permission</span>
            </a>
            <div id="navbarVerticalMenuAccess" class="nav-collapse collapse <?=(($pageSegment == 'modules' || $pageSegment == 'role' || $pageSegment == 'sub-user')?'show':'')?>" data-bs-parent="#navbarVerticalMenu">
              <a class="nav-link <?=(($pageSegment == 'modules')?'active':'')?>" href="<?=url('admin/modules/list')?>">Modules</a>
              <a class="nav-link <?=(($pageSegment == 'role')?'active':'')?>" href="<?=url('admin/role/list')?>">Roles</a>              
              <a class="nav-link <?=(($pageSegment == 'sub-user')?'active':'')?>" href="<?=url('admin/sub-user/list')?>">Sub Users</a>
            </div>
          </div>
        <!-- End Access & Permission -->
        
        <!-- contact enquires -->
          <div class="nav-item">
            <a class="nav-link <?=(($pageSegment == 'enquiry')?'active':'')?>" href="<?=url('admin/enquiry/list')?>" data-placement="left">
              <i class="fa fa-envelope nav-icon"></i>
              <span class="nav-link-title">Contact Enquires</span>
            </a>
          </div>
        <!-- End contact enquires -->
        <!-- page -->
          <div class="nav-item">
            <a class="nav-link <?=(($pageSegment == 'page')?'active':'')?>" href="<?=url('admin/page/list')?>" data-placement="left">
              <i class="fa fa-file nav-icon"></i>
              <span class="nav-link-title">Pages</span>
            </a>
          </div>
        <!-- End page -->
        
        <!-- email logs -->
          <div class="nav-item">
            <a class="nav-link <?=(($pageSegment == 'email-logs')?'active':'')?>" href="<?=url('admin/email-logs')?>" data-placement="left">
              <i class="fa fa-history nav-icon"></i>
              <span class="nav-link-title">Email Logs</span>
            </a>
          </div>
        <!-- End email logs -->
        <!-- login logs -->
          <div class="nav-item">
            <a class="nav-link <?=(($pageSegment == 'login-logs')?'active':'')?>" href="<?=url('admin/login-logs')?>" data-placement="left">
              <i class="fa fa-sign-in nav-icon"></i>
              <span class="nav-link-title">Login Logs</span>
            </a>
          </div>
        <!-- End login logs -->
      </div>
    </div>
    <!-- End Content -->
    <!-- Footer -->
    <div class="navbar-vertical-footer">
      <ul class="navbar-vertical-footer-list">
        <li class="navbar-vertical-footer-list-item">
          <!-- Style Switcher -->
          <div class="dropdown dropup">
            <!-- <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>
            </button> -->
            <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="selectThemeDropdown">
              <a class="dropdown-item" href="#" data-icon="bi-moon-stars" data-value="auto">
                <i class="bi-moon-stars me-2"></i>
                <span class="text-truncate" title="Auto (system default)">Auto (system default)</span>
              </a>
              <a class="dropdown-item" href="#" data-icon="bi-brightness-high" data-value="default">
                <i class="bi-brightness-high me-2"></i>
                <span class="text-truncate" title="Default (light mode)">Default (light mode)</span>
              </a>
              <a class="dropdown-item active" href="#" data-icon="bi-moon" data-value="dark">
                <i class="bi-moon me-2"></i>
                <span class="text-truncate" title="Dark">Dark</span>
              </a>
            </div>
          </div>
          <!-- End Style Switcher -->
        </li>
      </ul>
    </div>
    <!-- End Footer -->
  </div>
</div>