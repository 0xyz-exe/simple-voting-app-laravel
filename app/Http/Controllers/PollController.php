<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Vote;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::with('options', 'votes')->latest()->paginate(10);
        return view('polls.index', compact('polls'));
    }

    public function create()
    {
        return view('polls.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'allow_multiple' => 'boolean',
            'ends_at'        => 'nullable|date|after:now',
            'options'        => 'required|array|min:2|max:10',
            'options.*'      => 'required|string|max:255',
        ]);

        $poll = auth()->user()->polls()->create([
            'question'       => $validated['question'],
            'description'    => $validated['description'] ?? null,
            'allow_multiple' => $request->boolean('allow_multiple'),
            'ends_at'        => $validated['ends_at'] ?? null,
        ]);

        foreach (array_filter($validated['options']) as $opt) {
            $poll->options()->create(['option_text' => $opt]);
        }

        return redirect()->route('polls.show', $poll)->with('success', 'Poll created.');
    }

    public function show(Poll $poll)
    {
        $poll->load('options.votes', 'votes');
        $userVotedIds = $poll->votes()->where('user_id', auth()->id())->pluck('poll_option_id');
        $total = $poll->totalVotes();
        return view('polls.show', compact('poll', 'userVotedIds', 'total'));
    }

    public function vote(Request $request, Poll $poll)
    {
        if (!$poll->isOpen()) {
            return back()->withErrors(['error' => 'This poll is closed.']);
        }

        if ($poll->hasVoted(auth()->id()) && !$poll->allow_multiple) {
            return back()->withErrors(['error' => 'You have already voted.']);
        }

        $request->validate([
            'option_id'   => 'required_without:option_ids',
            'option_ids'  => 'required_without:option_id|array',
            'option_ids.*'=> 'exists:poll_options,id',
        ]);

        $optionIds = $request->input('option_ids', [$request->input('option_id')]);

        foreach ($optionIds as $optionId) {
            // prevent duplicate vote on same option
            Vote::firstOrCreate([
                'user_id'        => auth()->id(),
                'poll_id'        => $poll->id,
                'poll_option_id' => $optionId,
            ]);
        }

        return redirect()->route('polls.show', $poll)->with('success', 'Vote recorded!');
    }

    public function destroy(Poll $poll)
    {
        abort_unless(auth()->id() === $poll->user_id, 403);
        $poll->delete();
        return redirect()->route('polls.index')->with('success', 'Poll deleted.');
    }

    public function toggle(Poll $poll)
    {
        abort_unless(auth()->id() === $poll->user_id, 403);
        $poll->update(['is_active' => !$poll->is_active]);
        return back()->with('success', $poll->is_active ? 'Poll opened.' : 'Poll closed.');
    }
}
