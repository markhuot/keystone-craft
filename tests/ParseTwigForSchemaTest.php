<?php

use craft\helpers\App;
use markhuot\keystone\actions\CompileTwigComponent;
use markhuot\keystone\actions\GetComponentType;
use markhuot\keystone\actions\GetFileMTime;
use markhuot\keystone\factories\Component;
use function markhuot\craftpest\helpers\test\dump;
use function markhuot\craftpest\helpers\test\dd;

it('throws on unknown component', function () {
    $this->expectException(RuntimeException::class);
    (new GetComponentType)->byType('foo/bar');
});

it('throws on bad template path', function () {
    $this->expectException(RuntimeException::class);
    (new CompileTwigComponent('site:not-a-real-template.twig', 'test/not-a-real-handle-either'))->handle();
});

it('caches component types by modification date', function () {
    $fqcn = (new CompileTwigComponent('site:component-with-fields.twig', 'test/component-with-fields'))->handle();
    $hash = sha1('test/component-with-fields');
    $filemtime = filemtime(Craft::$app->getView()->resolveTemplate('component-with-fields.twig', \craft\web\View::TEMPLATE_MODE_SITE));
    expect(App::parseEnv('@runtime/compiled_classes/ComponentType'.$hash.$filemtime.'.php'))->toBeFile();
});

it('does not re-cache when unchanged', function () {

})->todo();

it('re-caches on modification', function () {
    $oneHourAgo = (new \DateTime)->sub(new \DateInterval('P60M'))->getTimestamp();
    $now = (new \DateTime)->getTimestamp();

    $hash = sha1('test/component-with-fields');
    $filepath = Craft::$app->getView()->resolveTemplate('component-with-fields.twig', \craft\web\View::TEMPLATE_MODE_SITE);
    $touchCacheAt = function ($timestamp) use ($hash, $filepath) {
        GetFileMTime::mock($filepath, $timestamp);
        (new CompileTwigComponent('site:component-with-fields.twig', 'test/component-with-fields'))->handle();
        expect(App::parseEnv('@runtime/compiled_classes/ComponentType'.$hash.$timestamp.'.php'))->toBeFile();
    };

    $touchCacheAt($oneHourAgo);
    $touchCacheAt($now);

    expect(App::parseEnv('@runtime/compiled_classes/ComponentType'.$hash.$oneHourAgo.'.php'))->not->toBeFile();
});

it('gets field and slot schema', function () {
    $fqcn = (new CompileTwigComponent('site:component-with-fields.twig', 'test/component-with-fields'))->handle();

    expect(new $fqcn)
        ->getFieldDefinitions()->toHaveCount(1)
        ->getSlotDefinitions()->toHaveCount(1);
});

it('gets exports', function () {
    $fqcn = (new CompileTwigComponent('site:export-icon.twig', 'test/export-icon'))->handle();
    (new $fqcn)->render(['exports' => $exports = new \markhuot\keystone\twig\Exports]);

    expect($exports)->icon->toBe('foo');
});
