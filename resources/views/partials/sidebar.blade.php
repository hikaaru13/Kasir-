<!-- resources/views/partials/sidebar.blade.php -->
@php
$role = session('user.role');
@endphp
<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            Kasir<span>ID</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            {{-- Looping menu --}}
            @foreach($menus as $menu)
                
                @php
                    $canRead = isset($menu['permissions'][0]['can_read']) ? $menu['permissions'][0]['can_read'] : null;
                @endphp

                @if($menu->menu_type_id == 1 && $canRead == 1)
                    <li class="nav-item">
                        <a href="{{ route('setActiveMenu', ['menu_id' => $menu->menu_id, 'redirect' => $menu->menu_redirect]) }}" class="nav-link">
                            <i class="link-icon" data-feather="{{ $menu->menu_icon }}"></i>
                            <span class="link-title">{{ $menu->menu }}</span> 
                        </a>
                    </li>
                @endif

                {{-- Submenu jika ada --}}
                @if($menu->submenus->isNotEmpty() && $menu->menu_type_id == 2)
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="collapse" href="#menu-{{ $menu->menu_id }}" role="button" aria-expanded="false" aria-controls="menu-{{ $menu->menu_id }}">
                            <i class="link-icon" data-feather="{{ $menu->menu_icon }}"></i> 
                            <span class="link-title">{{ $menu->menu }}</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse" id="menu-{{ $menu->menu_id }}">
                            <ul class="nav sub-menu">
                                @foreach($menu->submenus as $submenu)
                                    <li class="nav-item">
                                        <a href="{{ url($submenu->submenu_redirect) }}" class="nav-link">{{ $submenu->submenu }}</a> 
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @endif
            @endforeach
            
            @if($activeRole['role_id'] == 1 )

            <li class="nav-item nav-category">Settings</li>

            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#menu-settings" role="button" aria-expanded="false" aria-controls="menu-settings">
                    <i class="link-icon" data-feather="settings"></i> 
                    <span class="link-title">Settings</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="menu-settings">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('menus.index') }}" class="nav-link">Menu Setting</a> 
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link">Roles Management</a> 
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('menus.access') }}" class="nav-link">Menu Access</a> 
                        </li>
                    </ul>
                </div>
            </li>

            @endif
            
        </ul>
    </div>
</nav>
