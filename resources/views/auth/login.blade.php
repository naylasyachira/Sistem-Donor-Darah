@extends('layouts.app')

@section('content')
<div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto text-decoration-none">
                            <i class="bi bi-droplet-fill text-danger fs-1 me-2"></i>
                            <span class="d-none d-lg-block fw-bold fs-2 text-danger">RedPulse</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3" style="border-radius: 10px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1);">
                        <div class="card-body">
                            <div class="pt-4 pb-2 text-center">
                                <h5 class="card-title text-center pb-0 fs-4 text-dark fw-bold">Login to Your Account</h5>
                                <p class="text-center small text-muted">Connecting Lives Through Every Drop</p>
                            </div>

                            <form class="row g-3 needs-validation" method="POST" action="{{ route('login.post') }}">
                                @csrf
                                <div class="col-12">
                                    <label for="yourEmail" class="form-label">Email</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="email" name="email" class="form-control" id="yourEmail" required>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label for="yourPassword" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                                        <span class="input-group-text bg-white" id="togglePassword" style="cursor: pointer;">
                                            <i class="bi bi-eye" id="eyeIcon"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button class="btn w-100 text-white" style="background-color: var(--bs-primary);" type="submit">Login</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#yourPassword");
        const eyeIcon = document.querySelector("#eyeIcon");

        togglePassword.addEventListener("click", function () {
            // Toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // Toggle the icon
            if (type === "password") {
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        });
    });
</script>
@endsection
