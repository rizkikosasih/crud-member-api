<x-layouts.app>
    <div class="alert alert-error mt-4 hidden" id="alert-error"></div>

    <form id="user-form" class="max-w-md mx-auto mt-6">
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" id="name" class="input input-bordered w-full">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" id="email" class="input input-bordered w-full">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Hobbies</label>
            <div id="hobbies-wrapper">
                <div class="flex gap-2 mb-2 items-center">
                    <input type="text" name="hobbies[]" class="input input-bordered w-full" placeholder="Hobby">
                    <button type="button" class="btn btn-error btn-sm btn-remove">×</button>
                </div>
            </div>
            <button type="button" id="btn-add-hobby" class="btn btn-sm btn-outline mt-2 mb-4">Add Hobby</button>
        </div>

        <div class="flex gap-2">
            <button type="submit" id="btn-submit" class="btn btn-primary">
                <span>Save</span>
                <svg class="animate-spin hidden w-5 h-5 ml-2" id="spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
            <a href="/users" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('user-form');
                const btn = document.getElementById('btn-submit');
                const spinner = document.getElementById('spinner');
                const alertError = document.getElementById('alert-error');

                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const hobbiesInputs = form.querySelectorAll('input[name="hobbies[]"]');
                    const hobbies = Array.from(hobbiesInputs)
                        .map(input => input.value.trim())
                        .filter(h => h);

                    const data = {
                        name: form.name.value.trim(),
                        email: form.email.value.trim(),
                        hobbies: hobbies
                    };

                    spinner.classList.remove('hidden');
                    btn.disabled = true;
                    alertError.classList.add('hidden');

                    try {
                        const res = await axios.post('/users', data);
                        if (res.data.status) {
                            window.location.href = '/users';
                        } else {
                            alertError.textContent = res.data.message || 'Failed to create user';
                            alertError.classList.remove('hidden');
                        }
                    } catch (err) {
                        console.error(err);
                        alertError.textContent = err.response?.data?.message || 'Failed to create user';
                        alertError.classList.remove('hidden');
                    } finally {
                        spinner.classList.add('hidden');
                        btn.disabled = false;
                    }
                });

                document.getElementById('btn-add-hobby').addEventListener('click', () => {
                    const wrapper = document.getElementById('hobbies-wrapper');
                    const div = document.createElement('div');
                    div.classList.add('flex', 'gap-2', 'mb-2', 'items-center');
                    div.innerHTML = `
                        <input type="text" name="hobbies[]" class="input input-bordered w-full" placeholder="Hobby">
                        <button type="button" class="btn btn-error btn-sm btn-remove">×</button>
                    `;
                    wrapper.appendChild(div);

                    const lastInput = div.querySelector('input[name="hobbies[]"]');
                    if (lastInput) lastInput.focus();
                });

                document.getElementById('hobbies-wrapper').addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-remove')) {
                        e.target.closest('div').remove();
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
