<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item nav-profile">
        <a href="#" class="nav-link">
          <div class="profile-image">
            <img class="img-xs rounded-circle" src="{{ asset('backend/assets/images/faces/face8.jpg" alt="profile image') }}">
            <div class="dot-indicator bg-success"></div>
          </div>
          <div class="text-wrapper">
            <p class="profile-name">Allen Moreno</p>
            <p class="designation">Premium user</p>
          </div>
        </a>
      </li>
      <li class="nav-item nav-category">Main Menu</li>
      <li class="nav-item">
        <a class="nav-link" href="index.html">
          <i class="menu-icon typcn typcn-document-text"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('user.index') }}">
          <i class="menu-icon typcn typcn-user-outline"></i>
          <span class="menu-title">User</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('category.index') }}">
          <i class="menu-icon typcn typcn-user-outline"></i>
          <span class="menu-title">Category</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('brand.index') }}">
          <i class="menu-icon typcn typcn-user-outline"></i>
          <span class="menu-title">Brand</span>
        </a>
      </li>
    </ul>
  </nav>