<?php

namespace markhuot\keystone\attributes;

use markhuot\keystone\base\Attribute;

class SpaceBetween extends Attribute
{
    public function __construct(
        protected ?array $value = ['x' => null, 'y' => null],
    ) {
    }

    public function getInputHtml(): string
    {
        return \Craft::$app->getView()->renderTemplate('keystone/styles/space-between', [
            'label' => 'Space Between',
            'name' => get_class($this),
            'value' => $this->value ?? null,
        ]);
    }

    public function toAttributeArray(): array
    {
        return ['class' => implode(' ', array_filter([
            ($this->value['x'] ?? false) ? 'space-x-['.$this->value['x'].']' : null,
            ($this->value['y'] ?? false) ? 'space-y-['.$this->value['y'].']' : null,
        ]))];
    }
}
