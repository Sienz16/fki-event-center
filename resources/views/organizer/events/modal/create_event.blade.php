<!-- Create Event Modal -->
<div x-show="open" x-cloak @click.away="open = false" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
    <div class="bg-white rounded-lg w-full max-w-lg mx-4 sm:mx-6">
        <div class="flex items-center justify-between p-4 border-b">
            <h5 class="text-xl font-medium text-gray-900">Create New Event</h5>
            <button @click="open = false" class="text-gray-500 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-4 max-h-[75vh] overflow-y-auto">
            <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" id="event_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date & Time</label>
                    <input type="datetime-local" name="event_date" id="event_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="event_venue" class="block text-sm font-medium text-gray-700">Event Venue</label>
                    <input type="text" name="event_venue" id="event_venue" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                </div>
                <div class="mb-4">
                    <label for="event_desc" class="block text-sm font-medium text-gray-700">Event Description</label>
                    <textarea name="event_desc" id="event_desc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" rows="5" required></textarea>
                </div>

                <!-- Event Image Upload -->
                <div class="mb-4">
                    <label for="event_img" class="block text-sm font-medium text-gray-700">Event Image</label>
                    <input type="file" name="event_img" id="event_img" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="previewFile(event, 'image_preview')">
                    <div class="flex justify-center mt-4">
                        <img id="image_preview" class="w-32 h-32 object-contain" style="display:none;" />
                    </div>
                </div>

                <!-- Certificate Template Upload -->
                <div class="mb-4">
                    <label for="cert_template" class="block text-sm font-medium text-gray-700">Upload Certificate Template (Optional)</label>
                    <input type="file" name="cert_template" id="cert_template" accept=".pdf,image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="previewFile(event, 'cert_template_preview', 'cert_type')">
                    <div class="flex justify-center mt-4" id="cert_template_preview_container">
                        <!-- This will display the preview of the certificate template -->
                        <img id="cert_template_preview" class="w-32 h-32 object-contain" style="display:none;" />
                        <div id="cert_template_icon" class="flex justify-center items-center text-gray-500" style="display:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm">PDF Uploaded</span>
                        </div>
                    </div>
                </div>

                <!-- Certificate Orientation -->
                <div class="mb-4">
                    <label for="cert_orientation" class="block text-sm font-medium text-gray-700">Certificate Orientation</label>
                    <select name="cert_orientation" id="cert_orientation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="portrait" selected>Portrait</option>
                        <option value="landscape">Landscape</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="flex justify-end p-4 border-t">
            <button type="submit" form="createForm" class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create Event
            </button>
        </div>
    </div>
</div>

<script>
    // Function to preview image or PDF
    function previewFile(event, previewId, typeId) {
        const input = event.target;
        const preview = document.getElementById(previewId);
        const icon = document.getElementById(previewId + '_icon');
        const container = document.getElementById(previewId + '_container');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;

            if (fileType.includes('image')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    icon.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                preview.style.display = 'none';
                icon.style.display = 'flex';  // Show PDF icon
            }
        } else {
            preview.style.display = 'none';
            icon.style.display = 'none';
        }
    }
</script>
