<?php

namespace App\Livewire\Pages\Blog;

use App\Livewire\Pages\PageComponent;
use App\Models\BlogPost;
use App\Services\BlogSeoLinkService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class IndexPage extends PageComponent
{
    use WithPagination;

    #[Url(as: 'tema')]
    public string $category = '';

    #[Url(as: 'pretraga')]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->reset('search', 'category');
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function setCategory(string $category = ''): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    protected function title(): string
    {
        if ($this->category !== '') {
            return "{$this->category} | AutoIQ Blog";
        }

        return 'AutoIQ Blog | Vodiči, analize i saveti za kupovinu automobila';
    }

    protected function meta(): array
    {
        return [
            ...parent::meta(),
            'description' => 'AutoIQ Blog donosi analize tržišta, vodiče za kupovinu polovnjaka i praktične savete za izbor automobila u Srbiji.',
            'canonical' => route('blog.index', array_filter([
                'tema' => $this->category,
            ])),
            'type' => 'website',
            'robots' => $this->search !== '' ? 'noindex,follow' : 'index,follow',
        ];
    }

    protected function jsonLd(): array
    {
        $posts = BlogPost::query()
            ->published()
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->latest('published_at')
            ->limit(5)
            ->get();

        return [[
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            'name' => 'AutoIQ Blog',
            'description' => 'Analize tržišta, saveti za kupovinu i praktični vodiči za automobile u Srbiji.',
            'url' => route('blog.index', array_filter([
                'tema' => $this->category,
            ])),
            'blogPost' => $posts->map(fn (BlogPost $post) => [
                '@type' => 'BlogPosting',
                'headline' => $post->title,
                'url' => route('blog.show', $post),
                'datePublished' => optional($post->published_at)->toIso8601String(),
            ])->all(),
        ]];
    }

    public function render(): View
    {
        $baseQuery = BlogPost::query()
            ->published()
            ->when($this->category !== '', fn ($query) => $query->where('category', $this->category))
            ->when(trim($this->search) !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('title', 'like', '%'.trim($this->search).'%')
                ->orWhere('excerpt', 'like', '%'.trim($this->search).'%')));

        $featuredPost = (clone $baseQuery)
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->first();

        $posts = (clone $baseQuery)
            ->when($featuredPost, fn ($query) => $query->whereKeyNot($featuredPost->id))
            ->latest('published_at')
            ->paginate(6);

        $categories = BlogPost::query()
            ->published()
            ->select('category')
            ->selectRaw('count(*) as total')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->orderBy('category')
            ->get();

        return $this->page(view('livewire.pages.blog.index-page', [
            'featuredPost' => $featuredPost,
            'posts' => $posts,
            'categories' => $categories,
            'priorityGuides' => $this->category === '' && $this->search === ''
                ? app(BlogSeoLinkService::class)->priorityGuides(6)
                : collect(),
        ]));
    }
}
