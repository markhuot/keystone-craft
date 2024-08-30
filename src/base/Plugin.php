<?php

namespace markhuot\keystone\base;

use yii\base\Event;

/**
 * @phpstan-type EventHandler array{0: string, 1: string, 2: class-string<object&callable>}
 */
class Plugin extends \craft\base\Plugin
{
    const EVENT_INIT = 'init';

    public function init(): void
    {
        parent::init();

        \Craft::$container->set(\craft\web\Application::class, function () {
            if (! (\Craft::$app instanceof \craft\web\Application)) {
                throw new \RuntimeException('Craft::$app is not an instance of craft\web\Application');
            }

            return \Craft::$app;
        });

        \Craft::$container->set(\craft\console\Application::class, function () {
            if (! (\Craft::$app instanceof \craft\console\Application)) {
                throw new \RuntimeException('Craft::$app is not an instance of craft\console\Application');
            }

            return \Craft::$app;
        });

        $this->setAliases(['@keystone' => __DIR__.'/../']);

        foreach ($this->getListeners() as $listener) {
            $this->listen($listener);
        }

        $event = new \craft\base\Event();
        $event->sender = $this;
        Event::trigger(self::class, self::EVENT_INIT, $event);
    }

    /**
     * @return array<EventHandler>
     */
    protected function getListeners(): array
    {
        return [];
    }

    /**
     * @param EventHandler|callable(): EventHandler $event
     */
    function listen(mixed $event): void
    {

        if (is_callable($event)) {
            [$class, $event, $handlerClass] = $event();
        } else {
            [$class, $event, $handlerClass] = $event;
        }

        try {
            /** @var class-string<callable&object> $handlerClass */
            $handler = \Craft::$container->get($handlerClass);

            Event::on($class, $event, function (...$args) use ($handler) {
                return \Craft::$container->invoke($handler(...), $args);
            });
        } catch (\Throwable $e) {
            if (preg_match('/Class ".+" not found/', $e->getMessage())) {
                return;
            }

            throw $e;
        }
    }
}
