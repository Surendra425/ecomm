<header class="m-grid__item m-header "  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
    <div class="m-header__top">
        <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
            <div class="m-stack m-stack--ver m-stack--desktop">
                <!-- begin::Brand -->
                <div class="m-stack__item m-brand">
                    <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                        <div class="m-stack__item m-stack__item--middle m-brand__logo">
                            <a href="{{ url(route('vendorDashboard')) }}" class="m-brand__logo-wrapper">
                                <img alt="" src="{{ url('assets/logo.png') }}"/>
                            </a>
                        </div>
                        <div class="m-stack__item m-stack__item--middle m-brand__tools">
                            <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                                <span></span>
                            </a>
                            <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                <i class="flaticon-more"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- end::Brand -->
                <!-- begin::Topbar -->
                <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                    <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                        <div class="m-stack__item m-topbar__nav-wrapper">
                            <ul class="m-topbar__nav m-nav m-nav--inline">
                                <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                                    <a href="#" class="m-nav__link m-dropdown__toggle">
                                        <span class="m-topbar__userpic m--hide">
                                            <img src="{{ url('assets/app/media/img/users/user4.jpg') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                        </span>
                                        <span class="m-topbar__welcome">Hello,&nbsp;</span>
                                        <span class="m-topbar__username">{{ $loginVendor->first_name }}</span>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__header m--align-center" style="background: url({{ url('assets/app/media/img/misc/user_profile_bg.jpg') }}); background-size: cover;">
                                                <div class="m-card-user m-card-user--skin-dark">
                                                    <div class="m-card-user__pic">
                                                        <img src="{{ ($loginVendor->profile_image != "")? url('doc/vendor_logo').'/'.$loginVendor->profile_image : url('assets/demo/default/media/img/logo/user.png') }}" height="41px" width="41px" class="m--img-rounded m--marginless" alt="">
                                                    </div>

                                                    <div class="m-card-user__details">
                                                        <span class="m-card-user__name m--font-weight-500">{{ $loginVendor->first_name." ".$loginVendor->last_name }}</span>
                                                        <a href="#" class="m-card-user__email m--font-weight-300 m-link">{{ $loginVendor->email }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav m-nav--skin-light">
                                                        <li class="m-nav__section m--hide">
                                                            <span class="m-nav__section-text">Section</span>
                                                        </li>

                                                        <li class="m-nav__item">
                                                            <a href="{{ route('VendorProfile') }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                <span class="m-nav__link-title">  
                                                                    <span class="m-nav__link-wrap">      
                                                                        <span class="m-nav__link-text">My Profile</span>      
                                                                        <span class="m-nav__link-badge"></span>  
                                                                    </span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('change-passwords.index') }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon la la-lock"></i>
                                                                <span class="m-nav__link-title">
                                                                    <span class="m-nav__link-wrap">
                                                                        <span class="m-nav__link-text">Change Password</span>
                                                                    </span>
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit">
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('vendorLogout') }}" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">Logout</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end::Topbar -->
            </div>
        </div>
    </div>
    @if($loginVendor->pending_process == "No")
        <div class="m-header__bottom">
            <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
                <div class="m-stack m-stack--ver m-stack--desktop">
                    <!-- begin::Horizontal Menu -->
                    <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
                        <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
                        <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light "  >
                            <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                                <li class="m-menu__item  {{ (isset($pageTitle) && $pageTitle == 'dashboard') ? 'm-menu__item--active' : ''}}"  aria-haspopup="true">
                                    <a  href="{{ url(route('vendorDashboard')) }}" class="m-menu__link ">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Dashboard</span>
                                    </a>
                                </li>
                                <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ (isset($pageTitle) && (in_array($pageTitle,["Manage Store","Manage Shipping","Manage Social Media","Manage Bank Detail","Manage Subscription","Manage Deals","Manage Orders"]))? 'm-menu__item--active' : '')}}"  data-menu-submenu-toggle="click" aria-haspopup="true">
                                    <a  href="#" class="m-menu__link m-menu__toggle">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Manage</span>
                                        <i class="m-menu__hor-arrow la la-angle-down"></i>
                                        <i class="m-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                                        <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                        <ul class="m-menu__subnav">
                                            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Store")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('store.index')) }}" class="m-menu__link">
                                                    <i class="m-menu__link-icon flaticon-profile"></i>
                                                    <span class="m-menu__link-text">Manage Store</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Bank Detail")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('bank_detail.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-book"></i>
                                                    <span class="m-menu__link-text">Manage Bank Detail</span>
                                                </a>
                                            </li><li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Shipping")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('shipping.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-multimedia-1"></i>
                                                    <span class="m-menu__link-text">Manage Shipping</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Subscription")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('subscription.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-download"></i>
                                                    <span class="m-menu__link-text">Manage Subscription</span>
                                                </a>
                                            </li>
                                            {{-- <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Coupons")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                 <a  href="{{ url(route('coupons.index')) }}" class="m-menu__link ">
                                                     <i class="m-menu__link-icon flaticon-multimedia-1"></i>
                                                     <span class="m-menu__link-text">Manage Coupons</span>
                                                 </a>
                                             </li>
                                             <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Deals")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                 <a  href="{{ url(route('deals.index')) }}" class="m-menu__link ">
                                                     <i class="m-menu__link-icon flaticon-web"></i>
                                                     <span class="m-menu__link-text">Manage Deals</span>
                                                 </a>
                                             </li>--}}
                                            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Orders")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('my_order.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-web"></i>
                                                    <span class="m-menu__link-text">Manage Orders</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item {{ (isset($pageTitle) && $pageTitle=="Manage Social Media")?'m-menu__item--active':'' }}"  aria-haspopup="true">
                                                <a  href="{{ url(route('social-media.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-web"></i>
                                                    <span class="m-menu__link-text">Manage Social Media</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel {{ (isset($pageTitle) && (in_array($pageTitle,["productAdd","productList","product","Manage Categories","CollectionView","productCollectionList","productCollectionAdd"]))? 'm-menu__item--active' : '')}}"  data-menu-submenu-toggle="click" aria-haspopup="true">
                                    <a  href="#" class="m-menu__link m-menu__toggle">
                                        <span class="m-menu__item-here"></span>
                                        <span class="m-menu__link-text">Catalog</span>
                                        <i class="m-menu__hor-arrow la la-angle-down"></i>
                                        <i class="m-menu__ver-arrow la la-angle-right"></i>
                                    </a>
                                    <div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
                                        <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                        <ul class="m-menu__subnav">
                                            <li class="m-menu__item {{ (isset($pageTitle) && (in_array($pageTitle,["productAdd","productList","product"]))? 'm-menu__item--active' : '')}}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{{ url(route('products.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-squares"></i>
                                                    <span class="m-menu__link-text">Product</span>
                                                </a>
                                            </li>
                                            <li class="m-menu__item  {{ ((isset($pageTitle) && $pageTitle=="Manage Categories")? 'm-menu__item--active' : '')}}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{{ url(route('store-products-category.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-folder-2"></i>
                                                    <span class="m-menu__link-text">Category</span>
                                                </a>
                                            </li>
                                            {{--<li class="m-menu__item  {{ (isset($pageTitle) && (in_array($pageTitle,["CollectionView","productCollectionList","productCollectionAdd"]))? 'm-menu__item--active' : '')}}"  data-redirect="true" aria-haspopup="true">
                                                <a  href="{{ url(route('products-collections.index')) }}" class="m-menu__link ">
                                                    <i class="m-menu__link-icon flaticon-squares-4"></i>
                                                    <span class="m-menu__link-text">Product Collection</span>
                                                </a>
                                            </li>--}}
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- end::Horizontal Menu -->
                </div>
            </div>
        </div>
    @endif
</header>
