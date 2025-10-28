<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'CRUD MEMBER API' }}</title>

    <!-- CSS Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles tambahan per halaman -->
    @stack('styles')
    <script>
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
    </script>
</head>
<body class="min-h-screen bg-base-200">

@if($hasSidebar ?? false)
    <div class="drawer lg:drawer-open h-screen">
        <input id="my-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Sidebar -->
        <div class="drawer-side">
            <label for="my-drawer" class="drawer-overlay"></label>
            <div class="flex flex-col h-full w-64 bg-base-100">
                <div class="hidden lg:flex">
                    <x-layouts.navbar />
                </div>

                <x-layouts.sidebar />
            </div>
        </div>

        <!-- Main content -->
        <div class="drawer-content flex flex-col flex-1 overflow-auto">
            <div class="lg:hidden">
                <x-layouts.navbar />
            </div>

            <main class="p-6 flex-1 overflow-x-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
@else
    {{ $slot }}
@endif

<!-- Script tambahan per halaman -->
@stack('scripts')
<script type="module">
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            try {
                await axios.post('/logout');
            } catch (err) {
                console.error(err);
            } finally {
                localStorage.removeItem('token');

                window.location.href = '/login';
            }
        });
    }
</script>
</body>
</html>
