@extends('layouts.private.main')
@section('content')
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
      <div class="max-w-xl">
        <form method="POST" action="{{ route('register') }}">
          @csrf

          <!-- Name -->
          <div class="input-group input-group-outline mb-3">
            <x-input-label class="form-label" for="name" :value="__('Name')" />
            <x-text-input id="name" class="form-control block mt-1 w-full" type="text" name="name"
              :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
          </div>

          <!-- Email Address -->
          <div class="input-group input-group-outline mb-3">
            <x-input-label class="form-label" for="email" :value="__('Email')" />
            <x-text-input id="email" class="form-control block mt-1 w-full" type="email" name="email"
              :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
          </div>
          <!-- Phone Address -->
          <div class="input-group input-group-outline mb-3">
            <x-input-label class="form-label" for="phone" :value="__('Phone')" />
            <x-text-input id="phone" class="form-control block mt-1 w-full" type="text" name="phone"
              :value="old('phone')" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
          </div>

          <!-- Password -->
          <div class="input-group input-group-outline mb-3">
            <x-input-label class="form-label" for="password" :value="__('Password')" />

            <x-text-input id="password" class="form-control block mt-1 w-full" type="password" name="password" required
              autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
          </div>

          <!-- Confirm Password -->
          <div class="input-group input-group-outline mb-3">
            <x-input-label class="form-label" for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="form-control block mt-1 w-full" type="password"
              name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
          </div>

          <div class="flex items-center justify-end input-group input-group-outline mb-3">
            {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              href="{{ route('login') }}">
              {{ __('Already registered?') }}
            </a> --}}

            <x-primary-button class="ml-4">
              {{ __('Register') }}
            </x-primary-button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
