<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-black uppercase bg-purple-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Title</th>
                    <th scope="col" class="px-6 py-3">Details</th>
                    <th scope="col" class="px-6 py-3">Date and Time</th>
                    <th scope="col" class="px-6 py-3">Tag</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($news as $index => $newsItem)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $newsItem->news_title }}</td>
                        <td class="px-6 py-4">{{ $newsItem->news_details }}</td>
                        <td class="px-6 py-4">{{ $newsItem->date }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $newsItem->news_tag === 'Update' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $newsItem->news_tag === 'Maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $newsItem->news_tag === 'Bugs' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $newsItem->news_tag }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center space-y-2">
                                <a href="{{ route('admin.news.edit', $newsItem->news_id) }}" 
                                   class="inline-flex justify-center items-center w-full px-3 py-1.5 text-xs font-medium rounded-md text-white bg-purple-500 hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400">
                                    Edit
                                </a>
                                <button wire:click="deleteNews({{ $newsItem->news_id }})"
                                        class="inline-flex justify-center items-center w-full px-3 py-1.5 text-xs font-medium rounded-md text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No news found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $news->links() }}
    </div>
</div> 