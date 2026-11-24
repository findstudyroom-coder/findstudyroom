<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    height: 100vh;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
}

.login-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    color: #fff;
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from {opacity:0; transform: translateY(20px);}
    to {opacity:1; transform: translateY(0);}
}

.login-card h3 {
    font-weight: 600;
    text-align: center;
    margin-bottom: 25px;
}

.form-control {
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    border-radius: 10px;
    padding: 12px 15px;
}

.form-control:focus {
    background: rgba(255,255,255,0.3);
    box-shadow: none;
    color: #fff;
}

.form-control::placeholder {
    color: #eee;
}

.btn-login {
    background: #fff;
    color: #1e3c72;
    font-weight: 600;
    border-radius: 30px;
    padding: 10px;
    transition: 0.3s;
}

.btn-login:hover {
    background: #2a5298;
    color: #fff;
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
}

.forgot-link {
    color: #ddd;
    text-decoration: none;
}

.forgot-link:hover {
    color: #fff;
}

.logo {
    text-align: center;
    margin-bottom: 20px;
}

.logo img {
    width: 70px;
}

.alert {
    font-size: 14px;
    border-radius: 10px;
}
</style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <img src="{{ asset('public/assets/images/door.jpeg') }}" alt="logo">
    </div>

    <h3>Welcome Back 👋</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.signIn') }}" method="POST">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control" placeholder="Enter Email">
            @error('email')
                <small class="text-warning">{{ $message }}</small>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3 position-relative">
            <input type="password" name="password" id="password"
                   class="form-control" placeholder="Enter Password">
            <i class="fa fa-eye password-toggle text-white" id="togglePassword"></i>
            @error('password')
                <small class="text-warning">{{ $message }}</small>
            @enderror
        </div>

        <!-- Remember + Forgot -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <input type="checkbox" name="remember">
                <label> Remember me</label>
            </div>
            <a href="{{ route('admin.access.forgetPassword') }}" class="forgot-link">
                Forgot Password?
            </a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-login w-100">
            Sign In
        </button>

    </form>

</div>

<script>
document.getElementById("togglePassword").addEventListener("click", function () {
    const password = document.getElementById("password");
    const type = password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
    this.classList.toggle("fa-eye-slash");
});
</script>

</body>
</html>
