<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Poll</h2></x-slot>
    <div class="py-12"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form action="{{ route('polls.store') }}" method="POST" id="poll-form">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Question *</label>
                <input type="text" name="question" value="{{ old('question') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                @error('question')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4 flex gap-4">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="allow_multiple" value="1" {{ old('allow_multiple') ? 'checked' : '' }}> Allow multiple choices</label>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">End Date (optional)</label>
                <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('ends_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options (min 2) *</label>
                @error('options')<p class="text-red-600 text-sm mb-2">{{ $message }}</p>@enderror
                <div id="options-container" class="space-y-2">
                    @for($i = 0; $i < 4; $i++)
                    <div class="flex gap-2">
                        <input type="text" name="options[]" value="{{ old("options.$i") }}" placeholder="Option {{ $i + 1 }}" class="flex-1 rounded-md border-gray-300 shadow-sm" {{ $i < 2 ? 'required' : '' }}>
                        @if($i >= 2)<button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">✕</button>@endif
                    </div>
                    @endfor
                </div>
                <button type="button" id="add-option" class="mt-2 text-blue-600 hover:underline text-sm">+ Add Option</button>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Poll</button>
                <a href="{{ route('polls.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</a>
            </div>
        </form>
        <script>
        let count = 4;
        document.getElementById('add-option').addEventListener('click', function() {
            if (count >= 10) return;
            count++;
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `<input type="text" name="options[]" placeholder="Option ${count}" class="flex-1 rounded-md border-gray-300 shadow-sm"><button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">✕</button>`;
            document.getElementById('options-container').appendChild(div);
        });
        </script>
    </div></div></div>
</x-app-layout>
