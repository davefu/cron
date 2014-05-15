cron
====

Cron extension for Nette Framework

For installation, use CronExtension in your bootstrap or config file. It is necessary to create database table defined in cron.sql file.
However, this is required only when using MidnightJob or TimeIntervalJob.

Every task that should run as a cron task must implements IJob interface, or you can use CallbackJob to avoid this requirement.

There are two classes that provides delayed cron task execution. One is MidnightJob, that executes given IJob immediately after midnight.
Second is TimeIntervalJob, that repeatedly executes task every given time interval.

To handle job by Cron, you should define tag [cronJob] on every service in your config file.

For instance, if you have class FooClass that should be processed every 1 hour, you have to specify in your config file:

```
services:
  foo: FooClass
  fooJob:
    class: Foowie\Cron\Job\TimeIntervalJob('foo', '1 hour', @foo)
    tags: [cronJob]
```

Note that first parameter in class TimeIntervalJob is unique name of cron task. If FooClass does not implements IJob interface, you can use CallbackJob decorator.

```
services:
  foo: FooClass
  fooJobDecorator: Foowie\Cron\Job\CallbackJob([@foo, 'methodNameToExecute'])
  fooJob:
    class: Foowie\Cron\Job\TimeIntervalJob('foo', '1 hour', @fooJobDecorator)
    tags: [cronJob]
```

To select & execute tasks run Cron::run() method.

By default, the CronPresenter in FoowieCron module is mapped to url /cron/<token>. To use this presenter, it is needed to specify token in your config file with key securityToken.
To disable security token, set its value to null. To disable CronPresenter, set property mapPresenter in config to false.

There is also support for Doctrine 2. Default behavior is to autodetect used ORM. You can set it manually in config with key repositoryType [ndb, doctrine].
Entity Foowie\Cron\JobInfo has mapping metadata in src/Foowie/Cron/metadata/Foowie.Cron.JobInfo.dcm.xml. If you're using Kdyby\Doctrine, alternative KdybyDoctrineCronExtension also maps this entity.
