<form id="loginForm" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="alert alert-danger d-none" id="alert" role="alert">
        
    </div>
    <button type="button" class="btn btn-primary" onclick="login()" name="btnLogin">Login</button> or 
    <button type="button" class="btn btn-primary" onclick="window.location='register'">Register</button>
</form>