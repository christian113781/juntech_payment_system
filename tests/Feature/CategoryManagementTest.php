<?php

namespace Tests\Feature;

use App\Livewire\Admin\Category\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_category_page_can_create_a_category(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(Index::class)
            ->set('categoryName', 'Office Supplies')
            ->set('categoryDescription', 'Stationery and office materials')
            ->call('saveCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'name' => 'Office Supplies',
            'description' => 'Stationery and office materials',
        ]);
    }
}
