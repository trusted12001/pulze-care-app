<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center z-50">
    <div class="bg-white w-full max-w-md rounded-lg shadow p-6">
        <h3 class="text-xl font-bold mb-4">Add New User</h3>
        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-semibold">Name</label>
                <input type="text" name="name" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Email</label>
                <input type="email" name="email" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Password</label>
                <input type="password" name="password" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Role</label>
                <select name="role" class="w-full border p-2 rounded">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-semibold">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeUserModal()" class="bg-gray-400 px-4 py-2 rounded text-white">Cancel</button>
                <button type="submit" class="bg-blue-600 px-4 py-2 rounded text-white">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal() {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('userModal').classList.add('flex');
}
function closeUserModal() {
    document.getElementById('userModal').classList.add('hidden');
    document.getElementById('userModal').classList.remove('flex');
}
</script>
