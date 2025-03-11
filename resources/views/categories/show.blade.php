@props(['category', 'data', 'budgetData'])

@livewire('pages.categories.show', ['category' => $category, 'data' => $data], key($category->id))
