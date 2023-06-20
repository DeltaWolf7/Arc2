<?php
    $user = \Arc\ArcSystem::getData('user');
    print_r($user);
?>

<form id="profileForm" method="post">
    <div class="mb-3">
        <label for="firstname" class="form-label">Firstname</label>
        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['Firstname']; ?>">
    </div>
    <div class="mb-3">
        <label for="lastname" class="form-label">Lastname</label>
        <input type="text" class="form-control" id="lastname" name="lastname">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="mb-3">
        <label for="password2" class="form-label">Retype Password</label>
        <input type="password" class="form-control" id="password2" name="password2">
    </div>
    <div class="alert d-none" id="alert" role="alert">
    </div>
    <button type="button" class="btn btn-primary" onclick="updateProfile()">Update</button>
</form>