<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Log Aktivitas
                </h2>
                <p class="text-sm text-gray-500">Riwayat semua aktivitas pengguna sistem</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('activity-logs.export', request()->query()) }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Export CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            {{-- Filter Form --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Filter Aktivitas</h3>
                <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    {{-- Activity Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Aktivitas</label>
                        <select name="activity_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            @foreach($activityTypes as $type)
                                <option value="{{ $type }}" {{ $activityType == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                        <select name="action" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ $action == $act ? 'selected' : '' }}>
                                    {{ ucfirst($act) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- User --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <select name="user_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="from" value="{{ $from }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="to" value="{{ $to }}"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Filter Buttons --}}
                    <div class="lg:col-span-5 flex gap-2 pt-2">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Filter
                        </button>
                        <a href="{{ route('activity-logs.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Activities List --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">
                        Aktivitas Terbaru 
                        <span class="text-sm font-normal text-gray-500">({{ $activities->total() }} aktivitas)</span>
                    </h3>
                </div>

                @if($activities->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($activities as $activity)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-4">
                                    {{-- Icon --}}
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $activity->color_class }} text-sm">
                                            {{ $activity->icon }}
                                        </span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $activity->user ? $activity->user->name : 'System' }}
                                                <span class="font-normal text-gray-600">{{ $activity->description }}</span>
                                            </p>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $activity->activity_type }}</span>
                                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $activity->action }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center gap-4">
                                                <span>{{ $activity->created_at->format('d/m/Y H:i:s') }}</span>
                                                @if($activity->ip_address)
                                                    <span>IP: {{ $activity->ip_address }}</span>
                                                @endif
                                            </div>
                                            @if($activity->properties && count($activity->properties) > 0)
                                                <a href="{{ route('activity-logs.show', $activity) }}"
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    Detail
                                                </a>
                                            @endif
                                        </div>

                                        {{-- Subject Info --}}
                                        @if($activity->subject_type && $activity->subject)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ class_basename($activity->subject_type) }}: 
                                                    {{ $activity->subject->title ?? $activity->subject->name ?? 'ID #' . $activity->subject_id }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $activities->links() }}
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p>Tidak ada aktivitas yang ditemukan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>