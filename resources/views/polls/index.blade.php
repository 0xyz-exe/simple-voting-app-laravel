<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Polls</h2>
            <a href="{{ route('polls.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Poll</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>@endif
            @if($polls->isEmpty())
                <div class="bg-white shadow-sm sm:rounded-lg p-6 text-center text-gray-500">No polls yet. <a href="{{ route('polls.create') }}" class="text-blue-600 hover:underline">Create one!</a></div>
            @else
                <div class="space-y-4">
                    @foreach($polls as $poll)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $poll->question }}</h3>
                                @if($poll->description)<p class="text-gray-500 text-sm mt-1">{{ $poll->description }}</p>@endif
                                <div class="flex items-center gap-3 mt-2 text-sm text-gray-500">
                                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $poll->isOpen() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $poll->isOpen() ? 'Open' : 'Closed' }}
                                    </span>
                                    <span>{{ $poll->totalVotes() }} votes</span>
                                    <span>{{ $poll->options->count() }} options</span>
                                    @if($poll->ends_at)<span>Ends {{ $poll->ends_at->diffForHumans() }}</span>@endif
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('polls.show', $poll) }}" class="text-blue-600 hover:underline text-sm">View</a>
                                @if(auth()->id() === $poll->user_id)
                                    <form action="{{ route('polls.toggle', $poll) }}" method="POST" class="inline">@csrf<button type="submit" class="text-yellow-600 hover:underline text-sm">{{ $poll->is_active ? 'Close' : 'Open' }}</button></form>
                                    <form action="{{ route('polls.destroy', $poll) }}" method="POST" class="inline" onsubmit="return confirm('Delete poll?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline text-sm">Delete</button></form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $polls->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
