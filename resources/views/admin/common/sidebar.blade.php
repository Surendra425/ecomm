
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->

    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500"  >
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'dashboard') ? 'm-menu__item--active' : ''}}" aria-haspopup="true" >
                <a  href="{{route("adminDashboard")}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">  
                        <span class="m-menu__link-wrap">      
                            <span class="m-menu__link-text">Dashboard</span> 
                        </span> 
                    </span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'adminAdd') || ($pageTitle == 'adminList') || ($pageTitle == 'admin')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-user-plus"></i>
                    <span class="m-menu__link-text">Manage Admin</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu" >
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Admin</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'adminList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('adminList'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Admin List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{(isset($pageTitle) && $pageTitle == 'adminAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('adminCreate'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Admin</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'vendorAdd') || ($pageTitle =='vendorSalesUpdate') || ($pageTitle == 'vendorList') || ($pageTitle == 'vendor')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-user"></i>
                    <span class="m-menu__link-text">Manage Vendor</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Vendor</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'vendorList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('vendors.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Vendor List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'vendorAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('vendors.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Vendor</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'vendorSalesUpdate') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('vendorSalesEdit'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Vendor Sales(%) Update</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'customerAdd') || ($pageTitle == 'customerList') || ($pageTitle == 'customer')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-user"></i>
                    <span class="m-menu__link-text">Manage Customer</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Customer</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'customerList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('users.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Customer List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'customerAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('users.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Customer</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'storeAdd') || ($pageTitle == 'storesList') || ($pageTitle == 'Store')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-home"></i>
                    <span class="m-menu__link-text">Manage Store</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Store</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'storesList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('stores.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Store List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'storeAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('stores.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Store</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'storesCategoryAdd') || ($pageTitle == 'storesCategoryList') || ($pageTitle == 'StoreCategory')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon flaticon-layers"></i>
                    <span class="m-menu__link-text">Manage Store Category</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Store Category</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'storesCategoryList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('stores-category.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Store Category List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'storeAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('stores-category.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Store Category</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'productAdd') || ($pageTitle == 'productList') || ($pageTitle == 'product')) ? 'm-menu__item--open m-menu__item--expanded' : '' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-tasks"></i>
                    <span class="m-menu__link-text">Manage Product</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Product</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'productList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('products.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Product List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'productAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('products.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Product</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'recentProductList') || ($pageTitle == 'recentProduct')) ? 'm-menu__item--open m-menu__item--expanded' : '' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-tasks"></i>
                    <span class="m-menu__link-text">Manage Recent Product</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Recent Product</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'recentProductList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('recentProduct'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Recent Product List</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'productCategoryAdd') ||  ($pageTitle == 'productCategoryList') || ($pageTitle == 'productCategory')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-cubes"></i>
                    <span class="m-menu__link-text">Manage Product Category</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Product Category</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'productCategoryList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('products-category.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Product Category List</span>

                            </a>
                        </li>
                        <li class="m-menu__item " aria-has popup="true" >
                            <a  href="{{ (route('products-category.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Product Category</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'collectionAdd') ||  ($pageTitle == 'collectionList') || ($pageTitle == 'collection')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-bullhorn"></i>
                    <span class="m-menu__link-text">Manage Collection</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Collection</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'collectionList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('collections.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Collection List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'collectionAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('collections.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Collection</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'productCollectionAdd') ||  ($pageTitle == 'productCollectionList') || ($pageTitle == 'productCollectionView')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-bullhorn"></i>
                    <span class="m-menu__link-text">Manage Product Collection</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Product Collection</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'productCollectionList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('products-collections.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Product Collection List</span>

                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'countryAdd') ||  ($pageTitle == 'countryList') || ($pageTitle == 'country')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-globe"></i>
                    <span class="m-menu__link-text">Manage Country</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Country</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'countryList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('country.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Country List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'countryAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('country.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Country</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'eventAdd') ||  ($pageTitle == 'eventList') || ($pageTitle == 'event')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-globe"></i>
                    <span class="m-menu__link-text">Manage Event</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Events</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'eventList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('events.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Event List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'eventAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('events.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Event</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            {{--<li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'stateAdd') ||  ($pageTitle == 'stateList') || ($pageTitle == 'state')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-globe"></i>
                    <span class="m-menu__link-text">Manage State</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">State</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'stateList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('state.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">State List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'stateAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('state.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add State</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>--}}
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'cityAdd') ||  ($pageTitle == 'cityList') || ($pageTitle == 'city')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-globe"></i>
                    <span class="m-menu__link-text">Manage Area</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">City</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'cityList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('city.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Area List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'cityAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('city.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Area</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'planOptionList') || ($pageTitle == 'planOptionAdd') || ($pageTitle == 'planOption') || ($pageTitle == 'planAdd') ||  ($pageTitle == 'planList') || ($pageTitle == 'plan')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-money"></i>
                    <span class="m-menu__link-text">Manage Plan Subscriptions</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Plan Subscriptions</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'planList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('plans.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Plan List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'planAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('plans.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Plan</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'planOptionList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('plan-options.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Plan Option List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'planOptionAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('plan-options.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Plan Option</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'couponList') || ($pageTitle == 'couponAdd') || ($pageTitle == 'coupon')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-gift"></i>
                    <span class="m-menu__link-text">Manage Coupon</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Coupon</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'couponList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('coupons.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Coupon List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'couponAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('coupons.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Coupon</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m--hide m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'shippingClassAdd') || ($pageTitle == 'shippingClassList') || ($pageTitle == 'shippingClass')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-truck"></i>
                    <span class="m-menu__link-text">Manage Shipping Class</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Shipping Class</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'shippingClassList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('shipping-class.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Shipping Class List</span>

                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'shippingClassAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('shipping-class.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Shipping Class</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'appVersionAdd') ||  ($pageTitle == 'appVersionList')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-mobile"></i>
                    <span class="m-menu__link-text">Manage App</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">App</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'appVersionList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('versions.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">App Version List</span>

                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'advertisementAdd') || ($pageTitle == 'advertisementList') || ($pageTitle == 'advertisement')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-home"></i>
                    <span class="m-menu__link-text">Manage Advertisement</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Advertisement</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'advertisementList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('advertisement.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Advertisement List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'advertisementAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('advertisement.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Advertisement</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'orderList') || ($pageTitle == 'orderDetail') ? 'm-menu__item--active' : ''}}" aria-haspopup="true" >
                <a  href="{{route("orders.index")}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">  
                        <span class="m-menu__link-wrap">      
                            <span class="m-menu__link-text">Orders</span> 
                        </span> 
                    </span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'emailTemplateAdd') || ($pageTitle == 'emailTemplateList')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-envelope"></i>
                    <span class="m-menu__link-text">Manage Email Templates</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Email Templates</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'emailTemplateList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('email-template.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Email Templates List</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'emailTemplateAdd') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('email-template.create'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Add Email Templates</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && (($pageTitle == 'saleReport') || ($pageTitle == 'vendorSaleReport')) ) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-list"></i>
                    <span class="m-menu__link-text">Reports</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Reports</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'saleReport') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('sales.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Sales Reports</span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'vendorSaleReport') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{ (route('vendorSales.index'))}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Vendor Sales Reports</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle1) && ($pageTitle1 == 'pageAdd')) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon fa fa-file-text"></i>
                    <span class="m-menu__link-text">Content Management</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
                            <a  href="#" class="m-menu__link ">
                                <span class="m-menu__link-text">Content Management</span>
                            </a>
                        </li>
                        @if(!empty($content))
                            @foreach($content as $item)
                                <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == $item->slug) ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                                    <a  href="{{ route('pages.edit', ['pages' => $item->id])}}" class="m-menu__link ">
                                        <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                            <span></span>
                                        </i>
                                        <span class="m-menu__link-text">{{$item->page_name}}</span>

                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </li>

            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'push-notification') ? 'm-menu__item--active' : ''}}" aria-haspopup="true" >
                <a  href="{{route("push-notification.index")}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">Push Notification</span>
                        </span>
                    </span>
                </a>
            </li>

            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'subscribeList') ? 'm-menu__item--active' : ''}}" aria-haspopup="true" >
                <a  href="{{route("subscribe.index")}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">Subscribe User</span>
                        </span>
                    </span>
                </a>
            </li>


             <li class="m-menu__item  m-menu__item--submenu {{ (isset($pageTitle) && ($pageTitle == 'InquiryList') ||  ($pageTitle == 'Inquiryview') ) ? 'm-menu__item--open m-menu__item--expanded' : ''}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a href="#" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon la la-bullhorn"></i>
                    <span class="m-menu__link-text">Inquiry list</span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                       
                        <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle == 'InquiryList') ? 'm-menu__item--active' : ''}}" aria-has popup="true" >
                            <a  href="{{route("inquiry-lists.index")}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">Inquiry list</span>

                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>