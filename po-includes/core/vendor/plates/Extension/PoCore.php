<?php

namespace PoTemplate\Extension;

use PoTemplate\Engine;
use LogicException;

require_once 'po-includes/core/core.php';

/**
 * Extension that adds a number of URI checks.
 */
class PoCore implements ExtensionInterface
{
	/**
     * Instance of the current call.
     * @var Call
     */
    public $call;

    /**
     * Create new URI instance.
     */
    public function __construct()
    {
        $this->call = new \PoCore();
    }

	/**
     * Register extension functions.
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('pocore', [$this, 'getObject']);
    }

	/**
     * Create getObject function.
     */
	public function getObject()
    {
        return $this;
    }
}
