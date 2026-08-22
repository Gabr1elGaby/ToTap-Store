<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Plans') }}
            </h2>
            <a href="{{ route('admin.plans.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Plan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Product</th>
                                <th class="border-b py-2 px-4">Name</th>
                                <th class="border-b py-2 px-4">Price</th>
                                <th class="border-b py-2 px-4">Duration</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr>
                                    <td class="border-b py-2 px-4">{{ $plan->product->name }}</td>
                                    <td class="border-b py-2 px-4">{{ $plan->name }}</td>
                                    <td class="border-b py-2 px-4">Rp {{ number_format($plan->price, 0, ',', '.') }}</td>
                                    <td class="border-b py-2 px-4">{{ $plan->duration_days }} Days</td>
                                    <td class="border-b py-2 px-4">{{ $plan->is_active ? 'Active' : 'Inactive' }}</td>
                                    <td class="border-b py-2 px-4">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="text-blue-500">Edit</a>
                                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline" onsubmit="return confirm('Delete this plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 ml-2">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>