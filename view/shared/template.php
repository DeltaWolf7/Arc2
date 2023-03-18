<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title><?php Arc\ArcSystem::getTitle(); ?></title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" integrity="sha256-4RctOgogjPAdwGbwq+rxfwAmSpZhWaafcZR9btzUk18=" crossorigin="anonymous">
</head>

<body>

  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <div class="container-fluid">
      <a href="#" class="navbar-brand mb-0 h1">
        <img src="<?php ARC\ArcSystem::getAsset('images/logo.png'); ?>" height="32" width="32" alt="<?php ARC\ArcSystem::getTitle(); ?>" class="d-inline-block align-top" />
        Navbar
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-ctonrols="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item active">
            <a href="#" class="nav-link">Home</a>
          </li>

          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Features</a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li>
                <a href="#" class="dropdown-item">Feature 1</a>
              </li>
              <li>
                <a href="#" class="dropdown-item">Feature 2</a>
              </li>
              <li>
                <a href="#" class="dropdown-item">Feature 3</a>
              </li>
            </ul>
          </li>
        </ul>
        <div class="ms-auto">
          <form class="d-flex">
            <div class="input-group">
              <input type="text" class="form-control">
              <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <div class="container-fluid mt-3">
    <?php ARC\ArcSystem::getContent(); ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>