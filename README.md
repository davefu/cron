cron
====

Cron extension for Nette Framework

Compatibile with Nette >= 2.0

For installation, use Cron\CronExtension in your bootstrap or config file. It is necessary to create database table defined in cron.sql file. However, this is required only when using Cron\MidnightJob or Cron\TimeIntervalJob.

Every task that should run as a cron task must implements Cron\IJob interface, or you can use Cron\CallbackJob to avoid this requirement.

There are two classes that provides delayed cron task execution. One is Cron\MidnightJob, that executes given Cron\IJob imediately after midnight. Second is Cron\TimeIntervalJob, that repetively executes task every given time interval.

To handle job by Cron\Cron, you should define tag [cronJob] on every service in your config file.

For instance, if you have class FooClass that should be processed every 1 hour, you have to specify in your config file:

```
services:
  foo: FooClass
  fooJob:
    class: Cron\TimeIntervalJob('foo', '1 hour', @foo)
    tags: [cronJob]
```

Note that first parameter in class Cron\TimeIntervalJob is unique name of cron task. If FooClass does not implements Cron\IJob interface, you can use Cron\CallbackJob decorator.

```
services:
  foo: FooClass
  fooJobDecorator: Cron\CallbackJob([@foo, 'methodNameToExecute'])
  fooJob:
    class: Cron\TimeIntervalJob('foo', '1 hour', @fooJobDecorator)
    tags: [cronJob]
```
