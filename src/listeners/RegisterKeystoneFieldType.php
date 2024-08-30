<?php

namespace markhuot\keystone\listeners;

use craft\events\RegisterComponentTypesEvent;
use markhuot\keystone\fields\Keystone;

class RegisterKeystoneFieldType
{
    public function __invoke(RegisterComponentTypesEvent $event): void
    {
        $event->types[] = Keystone::class;
    }
}
