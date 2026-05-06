<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class NavigationComposer
{
    public function compose(View $view): void
    {
        $view->with('categories', Category::with('children')->whereNull('parent_id')->orderBy('name')->get());
    }
}
