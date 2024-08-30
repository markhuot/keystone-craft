<?php

namespace markhuot\keystone\listeners;

use craft\events\DefineCompatibleFieldTypesEvent;
use craft\fields\Matrix;
use markhuot\keystone\fields\Keystone;

class RegisterKeystoneFieldAsMatrixCompatible
{
    public function __invoke(DefineCompatibleFieldTypesEvent $event): void
    {
        if ($event->field instanceof Matrix) {
            $event->compatibleTypes[] = Keystone::class;
        }

        if ($event->field instanceof Keystone) {
            $event->compatibleTypes[] = Matrix::class;
        }
    }
}
