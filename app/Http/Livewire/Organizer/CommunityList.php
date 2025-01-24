<?php

namespace App\Http\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CommunityForum;

class CommunityList extends Component
{
    use WithPagination;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    protected $listeners = ['postAdded' => '$refresh'];

    public function sortBy($field)
    {
        $this->resetPage();
        
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function incrementLike($postId)
    {
        $post = CommunityForum::find($postId);
        if ($post) {
            $post->increment('likes');
            $post->save();
        }
    }

    public function incrementView($postId)
    {
        $post = CommunityForum::find($postId);
        if ($post) {
            $post->increment('views');
            $post->save();
        }
    }

    public function render()
    {
        return view('livewire.organizer.community-list', [
            'posts' => CommunityForum::orderBy($this->sortField, $this->sortDirection)
                ->paginate(10)
        ]);
    }
}
