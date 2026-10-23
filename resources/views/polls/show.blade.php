<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $poll->question }}</h2>
            <div class="flex gap-2">
                @if(auth()->id() === $poll->user_id)
                    <form action="{{ route('polls.toggle', $poll) }}" method="POST" class="inline">@csrf<button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-3 rounded text-sm">{{ $poll->is_active ? 'Close Poll' : 'Open Poll' }}</button></form>
                @endif
                <a href="{{ route('polls.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 rounded text-sm">Back</a>
            </div>
        </div>
    </x-slot>
    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>@endif
        @if($errors->has('error'))<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ $errors->first('error') }}</div>@endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            @if($poll->description)<p class="text-gray-600 mb-4">{{ $poll->description }}</p>@endif
            <div class="flex gap-3 mb-6 text-sm text-gray-500">
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $poll->isOpen() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $poll->isOpen() ? 'Open' : 'Closed' }}</span>
                <span>{{ $total }} total votes</span>
                @if($poll->ends_at)<span>Ends {{ $poll->ends_at->format('M d, Y H:i') }}</span>@endif
                @if($poll->allow_multiple)<span>Multiple choice allowed</span>@endif
            </div>

            {{-- Voting form --}}
            @if($poll->isOpen() && !$poll->hasVoted(auth()->id()))
            <form action="{{ route('polls.vote', $poll) }}" method="POST" class="mb-6">
                @csrf
                <div class="space-y-2 mb-4">
                    @foreach($poll->options as $option)
                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="{{ $poll->allow_multiple ? 'checkbox' : 'radio' }}"
                               name="{{ $poll->allow_multiple ? 'option_ids[]' : 'option_id' }}"
                               value="{{ $option->id }}" class="text-blue-600">
                        <span>{{ $option->option_text }}</span>
                    </label>
                    @endforeach
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">Cast Vote</button>
            </form>
            @endif

            {{-- Results --}}
            <div class="space-y-3">
                <h3 class="font-semibold text-gray-700">Results</h3>
                @foreach($poll->options as $option)
                @php $count = $option->voteCount(); $pct = $option->percentage($total); $voted = $userVotedIds->contains($option->id); @endphp
                <div class="{{ $voted ? 'bg-blue-50 border-blue-200' : 'bg-gray-50' }} border rounded-lg p-3">
                    <div class="flex justify-between mb-1">
                        <span class="font-medium text-sm {{ $voted ? 'text-blue-700' : '' }}">{{ $option->option_text }} @if($voted) ✓ @endif</span>
                        <span class="text-sm text-gray-600">{{ $count }} ({{ $pct }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div></div>
</x-app-layout>
