<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="custom-container">
            <form id="user-register"
                class="w-full p-4 grid gap-4 grid-cols-1 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
                enctype="multipart/form-data">
                @csrf

                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name"
                        :value="old('first_name')" required autofocus autocomplete="first_name" maxlength="512" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                        :value="old('last_name')" required autocomplete="last_name" maxlength="512" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Role ID -->
                <div>
                    <x-input-label for="role_id" :value="__('Role')" />
                    <x-select id="role_id" name="role_id" required>
                        <option value="" disabled selected>{{ __('Select a role') }}</option>
                        @foreach (getRoles() as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div>
                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                        :value="old('phone_number')" required maxlength="13" pattern="^\+91[6-9]\d{9}$"
                        title="Enter a valid Indian phone number (+91 followed by 10 digits)." />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email')" required autocomplete="username"
                        pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                        title="Enter a valid email address." />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-textarea id="description" name="description" rows="5" required></x-textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- File Upload -->
                <div>
                    <x-input-label for="profile_image" :value="__('Upload File')" />
                    <input id="profile_image"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        type="file" name="profile_image" required accept=".jpg,.jpeg,.png" />
                    <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button class="ms-4">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>


            <div id="response" class="hidden"></div>

            <table id="users-list"
                class="hidden bg-white dark:bg-gray-800 shadow-md min-w-full divide-y divide-gray-200 border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="p-2 text-center font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            First Name
                        </th>
                        <th scope="col"
                            class="p-2 text-center font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Last Name
                        </th>
                        <th scope="col"
                            class="p-2 text-center font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Role
                        </th>
                        <th scope="col"
                            class="p-2 text-center font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Email
                        </th>
                        <th scope="col"
                            class="p-2 text-center font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Phone Number
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#user-register').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('submit-form') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let user = response.user;

                        let isTableEmpty = $('#users-list tbody').children().length === 0;

                        if (isTableEmpty) {
                            $('#users-list').show();
                            let newRows = '';
                            response.users.forEach(function(user) {
                                newRows += `<tr>
									<td class="p-2 text-center border-b border-gray-200">${user.first_name}</td>
									<td class="p-2 text-center border-b border-gray-200">${user.last_name}</td>
									<td class="p-2 text-center border-b border-gray-200">${user.role_name}</td>
									<td class="p-2 text-center border-b border-gray-200">${user.email}</td>
									<td class="p-2 text-center border-b border-gray-200">${user.phone_number}</td>
								</tr>`;
                            });
                            $('#users-list > tbody').html(newRows);
                        } else {
                            let user = response.users[response.users.length - 1];
                            let newRow = `<tr>
								<td class="p-2 text-center border-b border-gray-200">${user.first_name}</td>
								<td class="p-2 text-center border-b border-gray-200">${user.last_name}</td>
								<td class="p-2 text-center border-b border-gray-200">${user.role_name}</td>
								<td class="p-2 text-center border-b border-gray-200">${user.email}</td>
								<td class="p-2 text-center border-b border-gray-200">${user.phone_number}</td>
							</tr>`;
                            $('#users-list > tbody').append(newRow);
                        }
                        alert(response.message);

                        setTimeout(() => {
                            $('#user-register')[0].reset();
                            $('html, body').animate({
                                scrollTop: $('table').offset().top
                            }, 500);
                        }, 250);
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul>';
                        for (let key in errors) {
                            errorMessage += `<li>${errors[key][0]}</li>`;
                        }
                        errorMessage += '</ul>';
                        $('#response').html('<p style="color: red;">' + errorMessage + '</p>')
                            .show();

                    },
                });
            });
        });
    </script>

</body>

</html>
