<?php

namespace markhuot\keystone\fields;

use Craft;
use craft\base\ElementInterface;
use craft\fields\Matrix;
use craft\web\Application;
use markhuot\keystone\actions\DuplicateComponentTree;
use markhuot\keystone\actions\GetComponentType;
use markhuot\keystone\models\Component;
use markhuot\keystone\models\ComponentData;
use function markhuot\keystone\helpers\base\app;

class Keystone extends Matrix
{
    public static function displayName(): string
    {
        return Craft::t('app', 'Keystone');
    }

    public static function icon(): string
    {
        return 'archway';
    }

    /**
     * @inheritDoc
     */
    protected function inputHtml(mixed $value, ?ElementInterface $element, bool $inline): string
    {
        return app(Application::class)
            ->getView()
            ->renderTemplate('keystone/field', [
                'element' => $element,
                'field' => $this,
                'value' => $value,
                'root' => true,
            ]);
    }
}
