<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Landing Page</title>
</head>


<body class="font-sans antialiased bg-white text-gray-800">
    @include('components.header')
    <div class="flex  flex-row justify-center ">
        <img src="{{ asset('images/THINKFast_app_logo.svg') }}" alt="Description of Image">
    </div>
    <div class="max-w-4xl mx-auto px-4 py-8">


        <div class="mb-8 max-w-md mx-auto">
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <x-text-input id="email" class="block w-full px-3 py-2 border border-gray-300 rounded-md"
                        type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        placeholder="Email" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-text-input id="password" class="block w-full px-3 py-2 border border-gray-300 rounded-md"
                        type="password" name="password" required autocomplete="current-password"
                        placeholder="Password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-primary-button
                        class="w-full justify-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ __('SIGN IN') }}
                    </x-primary-button>
                </div>
            </form>

            <div class="mt-4 flex justify-between text-sm">
                <a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">Forgot Your
                    Password?</a>
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
            </div>
        </div>

        <div class="text-center space-y-2 mb-8">
            <p>Installation instructions can be found <a href="#" class="text-blue-500 hover:underline">here</a>.</p>
            <p>Registration instructions for clinicians/educators can be found <a href="#"
                    class="text-blue-500 hover:underline">here</a>.</p>
        </div>

        <div class="flex justify-center space-x-8 ">
            <div class="flex flex-row  md:w-1/2 md:flex-col ">
                <div class="w-full">
                    <div class="border flex items-center justify-center h-32 m-2">
                        <img src="{{ asset('images/landingpage/stjosephs_london.png') }}"
                            alt="St Joseph's Health Care London" class="max-h-28 object-contain">
                    </div>
                </div>
                <div class="w-full">
                    <div class="border flex items-center justify-center h-32  space-y-4 m-2 ">
                        <img src="{{ asset('images/landingpage/lawson.png') }}" alt="Lawson Health Research Institute"
                            class="max-h-28 object-contain">
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>