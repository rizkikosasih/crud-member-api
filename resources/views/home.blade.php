<x-layouts.app>
    <div class="card card-border bg-base-100 w-full">
        <div class="card-body">
            <h2 class="card-title">Welcome,</h2>
            <p>Click the button below to view the list of users.</p>
            <div class="card-actions justify-end">
                <button class="btn btn-primary" href="{{ route('users.index') }}">Go to Users</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            const token = localStorage.getItem('token');
            console.log(token);
        </script>
    @endpush
</x-layouts.app>
