<div 
    x-data="{ 
        actionModalOpen: false,
        rejectModalOpen: false,
        init() {
            this.actionModalOpen = $wire.showModal;
            this.$watch('$wire.showModal', value => {
                this.actionModalOpen = value;
            });
            this.$watch('actionModalOpen', value => {
                $wire.showModal = value;
            });
        }
    }"
    x-init="init()"
    class="relative"
>
    <div class="mt-4 flex justify-center md:justify-start space-x-4">
        @if ($event->cert_template && $event->event_status === 'active')
            @if ($event->template_status === 'pending')
                <button wire:click="approveTemplate" 
                        class="bg-green-500 text-white px-4 py-2 rounded-md">
                    Approve Template
                </button>
                <button wire:click="rejectTemplate" 
                        class="bg-red-500 text-white px-4 py-2 rounded-md">
                    Reject Template
                </button>
            @endif
        @endif

        @if ($event->event_status === 'active')
            <button wire:click="openModal('suspend')" 
                    class="bg-yellow-500 text-white w-50 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                Suspend Event
            </button>
        @elseif ($event->event_status === 'suspended')
            <span class="text-red-500 text-sm font-medium">This event is under suspension, waiting organizer action.</span>
        @elseif ($event->event_status === 'pending')
            <button wire:click="openModal('reactivate')" 
                    class="bg-green-500 text-white w-50 px-4 py-2 rounded-md text-sm font-medium shadow-sm hover:bg-green-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Re-Activate Event
            </button>
        @endif
    </div>

    <x-event.action-modal>
        <x-slot:icon>
            @if($actionType === 'suspend')
                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            @else
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @endif
        </x-slot>

        <x-slot:iconBg>
            {{ $actionType === 'suspend' ? 'bg-yellow-100' : 'bg-green-100' }}
        </x-slot>

        <x-slot:title>
            {{ $actionType === 'suspend' ? 'Suspend Event' : 'Re-Activate Event' }}
        </x-slot>

        <x-slot:message>
            {{ $actionType === 'suspend' 
                ? 'Are you sure you want to suspend this event? This will temporarily hide the event from the public.'
                : 'Are you sure you want to re-activate this event? This will make the event visible to the public again.' }}
        </x-slot>

        <x-slot:confirmButton>
            {{ $actionType === 'suspend' ? 'Suspend' : 'Confirm' }}
        </x-slot>

        <x-slot:confirmButtonClass>
            {{ $actionType === 'suspend' ? 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }}
        </x-slot>
    </x-event.action-modal>
</div> 