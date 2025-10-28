<x-layouts.app :title="'Login Page'" :has-sidebar="false">
    <div class="flex items-center justify-center min-h-screen bg-base-200">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Login</h2>

                <div class="alert alert-error mb-4 hidden"></div>

                <form id="login-form">
                    @csrf

                    <div class="form-control">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" name="email" placeholder="email@example.com" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control mt-4">
                        <label class="label"><span class="label-text">Password</span></label>
                        <input type="password" name="password" placeholder="******" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary btn-block" id="login-btn">
                            <span>Login</span>
                            <svg id="spinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </button>
                    </div>

                    <p class="mt-4 text-center">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="link link-primary">Register</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            const form = document.getElementById('login-form');
            const btn = document.getElementById('login-btn');
            const spinner = document.getElementById('spinner');
            const alertError = document.querySelector('.alert-error');

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // show spinner, disable button
                spinner.classList.remove('hidden');
                btn.querySelector('span').textContent = 'Logging in...';
                btn.disabled = true;

                const formData = new FormData(form);
                const data = {
                    email: formData.get('email'),
                    password: formData.get('password')
                };

                try {
                    const response = await axios.post('/login', data);

                    if (response.data.status) {
                        // simpan token di localStorage
                        localStorage.setItem('token', response.data.data.token);

                        window.location.href = '/home'; // redirect setelah login
                    } else {
                        const message = response.data?.message || 'Login failed';
                        alertError.textContent = message;
                        alertError.classList.remove('hidden');
                    }
                } catch (err) {
                    console.error(err.response?.data || err);
                    alertError.textContent = err.response?.data?.message || 'Terjadi failed';
                    alertError.classList.remove('hidden');
                } finally {
                    // hide spinner, enable button
                    spinner.classList.add('hidden');
                    btn.querySelector('span').textContent = 'Login';
                    btn.disabled = false;
                }
            });
        </script>
    @endpush
</x-layouts.app>
