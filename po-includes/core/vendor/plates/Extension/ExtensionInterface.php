<?php

namespace PoTemplate\Extension;

use PoTemplate\Engine;

/**
 * A common interface for extensions.
 */
interface ExtensionInterface
{
    public function register(Engine $engine);
}
