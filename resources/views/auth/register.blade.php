<x-layouts.app :title="'Register Page'" :has-sidebar="false">
    <div class="flex items-center justify-center min-h-screen bg-base-200">
        <div class="card w-96 bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Register</h2>

                @if(session('error'))
                    <div class="alert alert-error mb-4">{{ session('error') }}</div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama</span></label>
                        <input type="text" name="name" placeholder="Your name" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control mt-4">
                        <label class="label"><span class="label-text">Email</span></label>
                        <input type="email" name="email" placeholder="email@example.com" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control mt-4">
                        <label class="label"><span class="label-text">Password</span></label>
                        <input type="password" name="password" placeholder="******" class="input input-bordered w-full" required>
                    </div>
                    <div class="form-control mt-6">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <p class="mt-4 text-center">
                        Already have an account?
                        <a href="{{ route('login') }}" class="link link-primary">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
