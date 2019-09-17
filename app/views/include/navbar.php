<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo URLROOT; ?>"><?php //echo SITENAME; ?>
            <img src="<?php echo URLROOT; ?>/img/brand/logo.svg" class="d-inline-block align-top" height="50" alt="<?php echo SITENAME; ?>">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault"
          aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
          <ul class="navbar-nav mr-auto">
            <?php if (isUserLoggedIn()) : ?>
            <!-- <li class="nav-item">
              <a class="nav-link" href="<?php //echo URLROOT; ?>">Home</a>
            </li> -->
            <li class="nav-item" id="nav-books">
              <a class="nav-link" href="<?php echo URLROOT; ?>/books/index">Books</a>
            </li>
            <li class="nav-item" id="nav-magazines">
              <a class="nav-link" href="<?php echo URLROOT; ?>/magazines/index">Magazines</a>
            </li>
            <?php endif ?>
          </ul>
          <ul class="navbar-nav ml-auto">
            <?php if (isset($_SESSION['user_id'])) : ?>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welocme <?php echo $_SESSION['user_name']; ?></a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/profile/<?php echo $_SESSION['user_id'] ?>">Profile</a>
                    <?php if (isAdminLoggedIn()) : ?>
                        <!-- <a class="dropdown-item" href="<?php //echo URLROOT; ?>/users/manageusers">Manage User</a> -->
                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/register">Register</a>
                    <?php endif ?>
                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/users/logout">Logout</a>
                  </div>
              </li>
            <?php else : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo URLROOT; ?>/users/login">Login</a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
    </div>
</nav>