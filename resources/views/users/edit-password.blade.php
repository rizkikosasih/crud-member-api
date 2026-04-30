<x-layouts.app>
  <div class="max-w-lg mx-auto mt-6">
    <h2 class="text-xl font-bold mb-4">Update Password</h2>

    <div class="alert alert-error my-4 hidden" id="alert-error"></div>
    <div class="alert alert-success my-4 hidden" id="alert-success"></div>

    <form id="update-password-form">
      @csrf

      <div class="mb-4">
        <label class="block mb-1">Current Password</label>
        <input type="password" id="current_password" class="input input-bordered w-full" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1">New Password</label>
        <input type="password" id="password" class="input input-bordered w-full" required />
      </div>

      <div class="mb-4">
        <label class="block mb-1">Confirm New Password</label>
        <input
          type="password"
          id="password_confirmation"
          class="input input-bordered w-full"
          required />
      </div>

      <div class="flex gap-2">
        <button type="submit" class="btn btn-primary">Update Password</button>
        <a href="/users" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('update-password-form');
      const alertError = document.getElementById('alert-error');
      const alertSuccess = document.getElementById('alert-success');

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        alertError.classList.add('hidden');
        alertSuccess.classList.add('hidden');

        const current_password = document.getElementById('current_password').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;

        try {
          const response = await axios.put('/profile/password', {
            current_password,
            password,
            password_confirmation,
          });

          alertSuccess.textContent = response.data.message || 'Password updated successfully.';
          alertSuccess.classList.remove('hidden');

          form.reset();
        } catch (err) {
          alertError.textContent = err.response?.data?.message || 'Failed to update password.';
          alertError.classList.remove('hidden');
        }
      });
    });
  </script>
</x-layouts.app>
