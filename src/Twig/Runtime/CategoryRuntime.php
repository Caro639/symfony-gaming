<?php

namespace App\Twig\Runtime;

use App\Repository\CategoryRepository;
use Twig\Extension\RuntimeExtensionInterface;

class CategoryRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
        // Inject dependencies if needed
    }

    public function getLastCategory(): array
    {
        $categories = $this->categoryRepository->findAll();
        // dd($categories);
        return $categories;
    }
}
