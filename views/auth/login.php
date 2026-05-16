<?php require __DIR__ . '/../layout/header.php'; ?>
<div class="container" style="min-height: 70vh; display: flex; align-items: center;">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            <div class="card shadow-lg">
                <div class="card-header text-center py-4">
                    <h2 class="mb-0" style="color: #00b34d; border: none; padding: 0;">Welcome Back</h2>
                </div>
                <div class="card-body p-5">
                    <p class="text-center text-muted mb-4">Sign in to manage your policies</p>
                    
                    <form method="post" action="index.php?action=login_post">
                        <div class="mb-4">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control form-control-lg" id="username" name="username" required placeholder="Enter your username" autofocus>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold mb-3">Sign In</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <div class="alert alert-info mb-2">
                        <strong><i class="fas fa-shield-alt"></i> Admin Credentials:</strong>
                        <div class="small mt-2">
                            Username: <code>admin</code><br>
                            Password: <code>admin123</code>
                        </div>
                    </div>

                    <div class="alert alert-info mb-2">
                        <strong><i class="fas fa-user-tie"></i> Officer Credentials:</strong>
                        <div class="small mt-2">
                            Username: <code>officer1</code><br>
                            Password: <code>officer123</code>
                        </div>
                    </div>

                    <div class="alert alert-info mb-0">
                        <strong><i class="fas fa-eye"></i> Viewer Credentials:</strong>
                        <div class="small mt-2">
                            Username: <code>viewer1</code><br>
                            Password: <code>viewer123</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../layout/footer.php'; ?>