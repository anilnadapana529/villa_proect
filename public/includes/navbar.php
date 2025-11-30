<style>
.navbar {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
    transition: transform 0.3s;
}

.navbar-brand:hover {
    transform: scale(1.05);
}

.nav-link {
    font-weight: 500;
    margin: 0 5px;
    transition: color 0.3s, transform 0.3s;
}

.nav-link:hover {
    color: #ffd700 !important;
    transform: translateY(-2px);
}

@media (max-width: 991px) {
    .navbar-collapse {
        background: rgba(102, 126, 234, 0.95);
        padding: 15px;
        border-radius: 8px;
        margin-top: 10px;
    }
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/">VillaBooking</a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/villas">Villas</a></li>
        <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
      </ul>
    </div>
  </div>
</nav>
