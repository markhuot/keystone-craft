<?php

namespace markhuot\keystone;

use craft\base\Element;
use craft\db\Query;
use craft\fields\PlainText;
use craft\services\Fields;
use craft\web\Application as WebApplication;
use craft\web\UrlManager;
use markhuot\keystone\actions\EagerLoadComponents;
use markhuot\keystone\actions\GetAttributeTypes;
use markhuot\keystone\actions\GetComponentType;
use markhuot\keystone\base\Plugin;
use markhuot\keystone\listeners\AttachElementBehaviors;
use markhuot\keystone\listeners\AttachFieldBehavior;
use markhuot\keystone\listeners\AttachPerRequestBehaviors;
use markhuot\keystone\listeners\AttachQueryBehaviors;
use markhuot\keystone\listeners\DiscoverSiteComponentTypes;
use markhuot\keystone\listeners\MarkClassesSafeForTwig;
use markhuot\keystone\listeners\RegisterCollectionMacros;
use markhuot\keystone\listeners\RegisterCpUrlRules;
use markhuot\keystone\listeners\RegisterDefaultAttributeTypes;
use markhuot\keystone\listeners\RegisterDefaultComponentTypes;
use markhuot\keystone\listeners\RegisterKeystoneFieldAsMatrixCompatible;
use markhuot\keystone\listeners\RegisterKeystoneFieldType;
use markhuot\keystone\listeners\RegisterTwigExtensions;
use markhuot\keystone\models\Component;

class Keystone extends Plugin
{
    protected function getListeners(): array
    {
        return [
            [Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, RegisterKeystoneFieldType::class],
            [Fields::class, Fields::EVENT_DEFINE_COMPATIBLE_FIELD_TYPES, RegisterKeystoneFieldAsMatrixCompatible::class],
        ];
    }
}
