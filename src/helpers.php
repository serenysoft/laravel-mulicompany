<?php

declare(strict_types=1);

use Sereny\MultiCompany\Manager;

if (! function_exists('company')) {
    /**
     * Get the current company manager.
     *
     * @return \Sereny\MultiCompany\Manager|null
     */
    function company()
    {
        if (! app()->bound(Manager::class)) {
            return;
        }

        return app(Manager::class);
    }
}
