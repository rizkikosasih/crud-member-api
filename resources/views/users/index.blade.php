<x-layouts.app>
    <div class="card card-border bg-base-100 w-full">
        <div class="card-body">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Users</h1>
                <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
            </div>

            <div class="mb-6 alert hidden" id="alert"></div>

            <div class="overflow-x-auto">
                <table class="table w-full table-auto">
                    <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Hoby</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="usersBody">
                        <tr><td colspan="5" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>

                <div class="flex justify-end mt-6 pagination" id="pagination"></div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <input type="checkbox" id="confirm-delete-modal" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Are you sure?</h3>
            <p class="py-4">Do you really want to delete this user? This action cannot be undone.</p>
            <div class="modal-action justify-end">
                <label for="confirm-delete-modal" class="btn btn-outline">Cancel</label>
                <button id="confirm-delete-btn" class="btn btn-error">Yes, Delete</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const usersBody = document.getElementById('usersBody');

                const paginationContainer = document.querySelector('.pagination');

                const renderPagination = (current, last) => {
                    paginationContainer.innerHTML = '';

                    const createBtn = (text, page, isActive = false, isDisabled = false) => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.textContent = text;
                        btn.className = `btn btn-sm ${isActive ? 'btn-primary' : 'btn-outline'} mx-1`;
                        if (isDisabled) btn.disabled = true;
                        // Gunakan event delegation, jadi listener cukup sekali di container
                        btn.setAttribute('data-page', page);
                        return btn;
                    };

                    // First
                    paginationContainer.appendChild(createBtn('First', 1, false, current === 1));

                    // Prev
                    paginationContainer.appendChild(createBtn('Prev', current - 1, false, current === 1));

                    // Menampilkan maksimum 5 halaman.
                    let start = Math.max(1, current - 2);
                    let end = Math.min(last, start + 4);

                    // Menyesuaikan start jika end sudah di halaman terakhir
                    if (end === last) {
                        start = Math.max(1, last - 4);
                    }

                    for (let i = start; i <= end; i++) {
                        paginationContainer.appendChild(createBtn(i, i, i === current));
                    }

                    // Next
                    paginationContainer.appendChild(createBtn('Next', current + 1, false, current === last));

                    // Last
                    paginationContainer.appendChild(createBtn('Last', last, false, current === last));
                };

                paginationContainer.addEventListener('click', (e) => {
                    console.log(e.target);
                    const btn = e.target.closest('button');
                    if (!btn) return;

                    const page = parseInt(btn.dataset.page);
                    if (!page || btn.disabled) return;
                    console.log(page)

                    loadUsers(page);
                });

                const loadUsers = async (page = 1) => {
                    try {
                        const res = await axios.get(`/users?page=${page}`);
                        usersBody.innerHTML = ''; // reset
                        res.data.data?.list.forEach((user, index) => {
                            const tr = document.createElement('tr');
                            tr.className = 'text-center';
                            tr.innerHTML = `
                                <td>${index + 1 + (res.data.data.current_page - 1) * res.data.data.per_page}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.hobbies?.map(h => h.name).join(', ') || '-'}</td>
                                <td class="flex gap-2 justify-center">
                                    <a href="/users/edit/${user.id}" class="btn btn-sm btn-primary">Edit</a>
                                    <button data-id="${user.id}" class="btn btn-sm btn-error btn-delete">Delete</button>
                                </td>
                            `;
                            usersBody.appendChild(tr);
                        });

                        renderPagination(res.data.data.current_page, res.data.data.last_page);

                        const drawerContent = document.querySelector('main');
                        drawerContent.scrollTo({ top: 0, behavior: 'smooth' });

                    } catch (err) {
                        console.error(err);
                        usersBody.innerHTML = `<tr><td colspan="5" class="text-center text-error">Failed to load users</td></tr>`;
                    }
                };

                loadUsers();

                // event listener untuk delete user
                let userIdToDelete = null;
                usersBody.addEventListener('click', (e) => {
                    const btn = e.target.closest('.btn-delete');
                    if (!btn) return;

                    userIdToDelete = btn.dataset.id;

                    document.getElementById('confirm-delete-modal').checked = true;
                });

                document.getElementById('confirm-delete-btn').addEventListener('click', async () => {
                    if (!userIdToDelete) return;
                    const alert = document.getElementById('alert');

                    try {
                        const res = await axios.delete(`/users/${userIdToDelete}`);
                        // hapus row
                        const row = usersBody.querySelector(`[data-id='${userIdToDelete}']`)?.closest('tr');
                        if (row) row.remove();

                        alert.classList.remove('alert-error', 'hidden');
                        alert.classList.add('alert-success');
                        alert.textContent = res.data?.message || 'User deleted successfully';

                    } catch (err) {
                        console.error(err);

                        alert.classList.remove('alert-success', 'hidden');
                        alert.classList.add('alert-error');
                        alert.textContent = err.response?.data?.message || 'Failed to delete user';

                    } finally {
                        setTimeout(() => {
                            alert.classList.add('hidden');
                            alert.classList.remove('alert-success', 'alert-error');
                            alert.textContent = '';
                            userIdToDelete = null;
                            document.getElementById('confirm-delete-modal').checked = false; // tutup modal
                        }, 3000);
                    }
                });
            });
        </script>
    @endpush
</x-layouts.app>
