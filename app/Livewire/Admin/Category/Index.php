<?php

namespace App\Livewire\Admin\Category;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app', ['title' => 'Categories'])]
class Index extends Component
{
    public array $categories = [];

    public string $search = '';

    public int $perPage = 10;

    public int $currentPage = 1;

    public int $totalCategories = 0;

    public ?int $deleteCategoryId = null;

    public bool $showCreateModal = false;

    public bool $showEditModal = false;

    public ?int $editingCategoryId = null;

    public string $categoryName = '';

    public string $categoryDescription = '';

    public function mount(): void
    {
        $this->loadCategories();
    }

    public function loadCategories(): void
    {
        $query = Category::query()->withCount('products')->orderBy('name');

        if ($this->search !== '') {
            $needle = strtolower(trim($this->search));

            $query->where(function ($q) use ($needle) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . $needle . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $needle . '%']);
            });
        }

        $this->categories = $query->get()->map(function (Category $category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description ?: 'No description',
                'product_count' => $category->products_count,
            ];
        })->toArray();

        $this->totalCategories = count($this->categories);
        $this->perPage = max(1, $this->perPage);

        $totalPages = max(1, (int) ceil($this->totalCategories / $this->perPage));
        $this->currentPage = max(1, min((int) $this->currentPage, $totalPages));

        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->categories = array_slice($this->categories, $offset, $this->perPage);
    }

    public function updatedSearch(): void
    {
        $this->currentPage = 1;
        $this->loadCategories();
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);

        $this->editingCategoryId = $category->id;
        $this->categoryName = $category->name;
        $this->categoryDescription = $category->description ?? '';
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingCategoryId = null;
        $this->resetForm();
    }

    public function saveCategory(): void
    {
        $this->validate([
            'categoryName' => ['required', 'string', 'max:255'],
            'categoryDescription' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->showEditModal && $this->editingCategoryId) {
            $category = Category::findOrFail($this->editingCategoryId);
            $category->update([
                'name' => trim($this->categoryName),
                'description' => trim($this->categoryDescription),
            ]);

            session()->flash('success', 'Category updated successfully.');
            $this->dispatch('toast', message: 'Category updated successfully.');
        } else {
            Category::create([
                'name' => trim($this->categoryName),
                'description' => trim($this->categoryDescription),
            ]);

            session()->flash('success', 'Category created successfully.');
            $this->dispatch('toast', message: 'Category created successfully.');
        }

        $this->closeCreateModal();
        $this->closeEditModal();
        $this->loadCategories();
    }

    public function openDeleteModal(int $categoryId): void
    {
        $this->deleteCategoryId = $categoryId;
    }

    public function closeDeleteModal(): void
    {
        $this->deleteCategoryId = null;
    }

    public function confirmDelete(): void
    {
        if (! $this->deleteCategoryId) {
            return;
        }

        $category = Category::withCount('products')->find($this->deleteCategoryId);

        if ($category) {
            if ($category->products_count > 0) {
                session()->flash('error', 'This category cannot be deleted because it is still assigned to products.');
                $this->dispatch('toast', message: 'This category cannot be deleted because it is still assigned to products.');
                $this->deleteCategoryId = null;
                return;
            }

            $category->delete();
            session()->flash('success', 'Category deleted successfully.');
            $this->dispatch('toast', message: 'Category deleted successfully.');
        }

        $this->deleteCategoryId = null;
        $this->loadCategories();
    }

    public function previousPage(): void
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->loadCategories();
        }
    }

    public function nextPage(): void
    {
        $totalPages = max(1, (int) ceil($this->totalCategories / $this->perPage));

        if ($this->currentPage < $totalPages) {
            $this->currentPage++;
            $this->loadCategories();
        }
    }

    public function goToPage(int $page): void
    {
        $totalPages = max(1, (int) ceil($this->totalCategories / $this->perPage));
        $this->currentPage = max(1, min($page, $totalPages));
        $this->loadCategories();
    }

    protected function resetForm(): void
    {
        $this->categoryName = '';
        $this->categoryDescription = '';
        $this->editingCategoryId = null;
    }

    public function render()
    {
        $totalPages = max(1, (int) ceil($this->totalCategories / $this->perPage));
        $startItem = $this->totalCategories === 0 ? 0 : (($this->currentPage - 1) * $this->perPage) + 1;
        $endItem = min($this->currentPage * $this->perPage, $this->totalCategories);

        return view('livewire.admin.category.index', [
            'categories' => $this->categories,
            'totalCategories' => $this->totalCategories,
            'currentPage' => $this->currentPage,
            'startItem' => $startItem,
            'endItem' => $endItem,
            'totalPages' => $totalPages,
            'deleteCategory' => $this->deleteCategoryId ? Category::find($this->deleteCategoryId) : null,
        ]);
    }
}
