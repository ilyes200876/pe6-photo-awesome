<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class PriceExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    // {{ media.price|euro }}
    public function euro(int $value)
    {
    $newPrice = $value / 100;
        return number_format($newPrice,2 ,",");
    }
}
