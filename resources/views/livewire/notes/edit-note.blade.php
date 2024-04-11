<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Note;


new #[layout('layouts.app')] class extends Component {
    public Note $note;

    public $noteTitle;
    public $noteBody;
    public $noteRecipient;
    public $noteSendDate;
    public $noteIsPublished;

    public function mount(Note $note)
    {
        $this->authorize('update', $note);
        $this->fill($note);
        $this->noteTitle = $note->title;
        $this->noteBody = $note->body;
        $this->noteRecipient = $note->recipient;
        $this->noteSendDate = $note->send_date;
        $this->noteIsPublished = $note->is_published;
    }

    public function submit()
    {
        $validated = $this->validate([
            'noteTitle' => ['required', 'string', 'min:5'],
            'noteBody' => ['required', 'string', 'min:20'],
            'noteRecipient' => ['required', 'email'],
            'noteSendDate' => ['required', 'date'],
        ]);
        

        $this->note->update([
            'title' => $this->noteTitle,
            'body' => $this->noteBody,
            'recipient' => $this->noteRecipient,
            'send_date' => $this->noteSendDate,
            'is_published' => $this->noteIsPublished,
        ]);

        $this->dispatch('note-saved');
    }
}; ?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Note!') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <form wire:submit="submit" class="space-y-4">
        <x-input wire:model="noteTitle" label="Note Title" placeholder="It's been a great day"/>
        <x-textarea wire:model="noteBody" label="Your Note" placeholder="Share all your thoughts with your friend"/>
        <x-input wire:model="noteRecipient" icon="user" type="email" label="Recipient" placeholder="yourfriend@email.com"/>
        <x-input wire:model="noteSendDate" icon="calendar" type="date" label="Send Date" />
        <x-checkbox wire:model="noteIsPublished" label="Note published"/>
        <div class="pt-4 flex justify-between">
            <x-button icon="arrow-left" class="mb=8" href="{{ route('notes.index') }}" flat negative>Back to Notes</x-button>
            <x-button type="submit" primary right-icon="calendar" spinner="submit">Update Note</x-button>
        </div>
        <x-action-message on="note-saved"/>
        <x-errors />
    </form>
    </div>
</div>
