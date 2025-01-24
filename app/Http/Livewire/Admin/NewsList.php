<?php

namespace App\Http\Livewire\Admin;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class NewsList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.news-list', [
            'news' => News::with('admin')->paginate(10)
        ]);
    }

    public function deleteNews($newsId)
    {
        try {
            $news = News::findOrFail($newsId);
            $news->delete();
            
            session()->flash('success', 'News deleted successfully');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete news');
        }
    }
} 