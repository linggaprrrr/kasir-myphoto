<!-- main header @s -->
<div class="nk-header is-light nk-header-fixed is-light">
    <div class="container-xl wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1 me-3">
                <a href="/dirs" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="/" class="logo-link">
                    <img class="logo-light logo-img" src="../images/LOGO 5.png" srcset="../images/LOGO 5.png 2x" alt="logo">
                    <img class="logo-dark logo-img" src="../images/LOGO 5.png" srcset="../images/LOGO 5.png 2x" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->            
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">                    
                     
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info">
                                    <span class="lead-text"><?= ucfirst(session()->get('username')) ?></span>
                                    <span class="sub-text"><?= ucfirst(session()->get('user_type')) ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            
                            <div class="dropdown-inner">
                                <ul class="link-list">                                
                                    <li><a href="/logout"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>  
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
