<x-app-layout>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                                Weather alert
                            </h1>

                            <p class="mt-6 text-gray-500 leading-relaxed">
                                Welcome to the monitoring panel!  <br />
                                Here, you’ll see graphs displaying your data.  <br />
                                If no graphs are visible, please <a class="inline-flex items-center font-semibold text-indigo-700" href="{{ route('profile.show') }}">go to your profile</a> and add your country and city along with your maximum threshold values for notifications.
                            </p>
                        </div>


                        @auth
                            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 p-6 lg:p-8">
                                <livewire:chart />
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>