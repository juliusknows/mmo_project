<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public function __construct(Request $request);
}
