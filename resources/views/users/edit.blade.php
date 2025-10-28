<x-layouts.app>
    <div class="max-w-lg mx-auto mt-6">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>

        <div class="alert alert-error mt-4 hidden" id="alert-error"></div>
        <div class="alert alert-success mt-4 hidden" id="alert-success"></div>

        <form id="edit-user-form">
            @csrf
            <input type="hidden" id="user-id" value="{{ $userId }}">

            <div class="mb-4">
                <label class="block mb-1">Name</label>
                <input type="text" id="name" class="input input-bordered w-full" value="" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" id="email" class="input input-bordered w-full" value="" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Hobbies</label>
                <div id="hobbies-container"></div>
                <button type="button" id="add-hobby" class="btn btn-sm btn-outline mt-2">+ Add Hobby</button>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="/users" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('edit-user-form');
            const alertError = document.getElementById('alert-error');
            const alertSuccess = document.getElementById('alert-success');
            const hobbiesContainer = document.getElementById('hobbies-container');
            const addHobbyBtn = document.getElementById('add-hobby');
            const userId = document.getElementById('user-id').value;

            function addHobbyInput(value = '') {
                const div = document.createElement('div');
                div.classList.add('flex', 'gap-2', 'mb-2', 'items-center');
                div.innerHTML = `
                    <input type="text" class="input input-bordered w-full hobby-input" value="${value}" placeholder="New hobby">
                    <button type="button" class="btn btn-error btn-sm remove-hobby">x</button>
                `;
                hobbiesContainer.appendChild(div);
                div.querySelector('input').focus();
            }


            addHobbyBtn.addEventListener('click', () => addHobbyInput());

            hobbiesContainer.addEventListener('click', (e) => {
                if(e.target.classList.contains('remove-hobby')){
                    e.target.parentElement.remove();
                }
            });

            async function loadUser() {
                try {
                    const response = await axios.get(`/users/${userId}`);
                    const user = response.data.data;

                    document.getElementById('name').value = user.name;
                    document.getElementById('email').value = user.email;

                    hobbiesContainer.innerHTML = '';
                    if(user.hobbies && user.hobbies.length){
                        user.hobbies.forEach(h => addHobbyInput(h.name));
                    }
                } catch (err) {
                    alertError.textContent = err.response?.data?.message || 'Failed to load user data.';
                    alertError.classList.remove('hidden');
                }
            }

            loadUser();

            // Submit form
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                alertError.classList.add('hidden');
                alertSuccess.classList.add('hidden');

                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const hobbies = Array.from(document.querySelectorAll('.hobby-input'))
                    .map(i => i.value)
                    .filter(v => v.trim() !== '');

                try {
                    const response = await axios.put(`/users/${userId}`, { name, email, hobbies });
                    alertSuccess.textContent = 'User updated successfully!';
                    alertSuccess.classList.remove('hidden');
                } catch (err) {
                    alertError.textContent = err.response?.data?.message || 'Failed to update user.';
                    alertError.classList.remove('hidden');
                }
            });
        });
    </script>
</x-layouts.app>
