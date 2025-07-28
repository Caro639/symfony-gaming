<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TextExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function transformDescription($description)
    {
        // dd($description);
        return substr($description, 0, 100);
    }
}
