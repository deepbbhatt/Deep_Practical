<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main>
  <div class="container py-4">
    <header class="pb-3 mb-4 border-bottom">
      <div class="row align-items-center">
        <div class="col-md-11">
          <a href="/" class="d-flex align-items-center text-dark text-decoration-none">
            <img src="{{ asset('asset/images/logo.png') }}" alt="Sample Logo" width="40" class="me-2">
            <span class="fs-4 fw-bold">{{ config('app.name', 'Laravel') }}</span>
          </a>
        </div>
        <div class="col-md-1 text-end">
          @auth
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
              {{ Auth::user()->first_name ?? 'User' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
              <li>
                <a class="dropdown-item" href="{{ route('profile.show') }}"><img src="{{ asset('asset/images/user.png') }}" class="rounded-circle me-1" width="24" height="24">
        Profile</a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="GET" class="m-0">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
          @endauth
        </div>
      </div>
    </header>

    <div class="p-4 bg-light rounded-3">
      <div class="container-fluid py-4">
        @yield('content')
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
