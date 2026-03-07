@extends($layout)

@section('title', 'My Account')

@section('content')
    <div class="min-h-screen p-4 sm:p-6 lg:p-8">
        <div class="max-w-5xl mx-auto">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Account</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Manage your phone number, address, and password.
                    </p>
                </div>

                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    ← Back
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('password_success'))
                <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-800">
                    {{ session('password_success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Contact Information</h2>

                    <form method="POST" action="{{ route('account.settings.profile.update') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Full Name</label>
                                <input type="text"
                                    value="{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?? '') }}"
                                    class="w-full bg-gray-100 border border-gray-300 rounded px-4 py-2 text-gray-700"
                                    disabled>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Email</label>
                                <input type="email" value="{{ $user->email }}"
                                    class="w-full bg-gray-100 border border-gray-300 rounded px-4 py-2 text-gray-700"
                                    disabled>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="e.g. 07824020468">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Address Line 1</label>
                                <input type="text" name="address_line_1"
                                    value="{{ old('address_line_1', $user->address_line_1) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="House number and street">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Address Line 2</label>
                                <input type="text" name="address_line_2"
                                    value="{{ old('address_line_2', $user->address_line_2) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="Apartment, suite, unit, building">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Town / City</label>
                                <input type="text" name="town_city" value="{{ old('town_city', $user->town_city) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="e.g. Luton">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">County</label>
                                <input type="text" name="county" value="{{ old('county', $user->county) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="e.g. Bedfordshire">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Postcode</label>
                                <input type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="e.g. LU4 9JR">
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                                    Save Contact Info
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Change Password -->
                <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>

                    <form method="POST" action="{{ route('account.settings.password.update') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Current Password</label>
                                <input type="password" name="current_password"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="Enter current password">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">New Password</label>
                                <input type="password" name="password"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="Enter new password">
                            </div>

                            <div>
                                <label class="block text-sm text-gray-600 mb-1">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2 text-gray-800"
                                    placeholder="Confirm new password">
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="bg-gray-800 text-white px-5 py-2 rounded hover:bg-gray-900 transition">
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection