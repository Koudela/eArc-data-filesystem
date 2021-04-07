# eArc-data-filesystem

Filesystem bridge providing a basic entity database/value-store/backup-system for 
the [earc/data](https://github.com/Koudela/eArc-data) abstraction.

## installation

Install the earc/data-filesystem library via composer.

```bash
$ composer require earc/data-filesystem
```

## basic usage

Initialize the earc/data abstraction in your index.php, bootstrap or configuration
script.

```php
use eArc\Data\Initializer;

Initializer::init();
```

Then register the earc/data-filesystem bridge to the earc/data `onLoad`, 
`onPersit`, `onRemove` and `onFind` events and set the data path for your filesystem.

```php
use eArc\Data\ParameterInterface;
use eArc\DataFilesystem\FilesystemDataBridge;

di_tag(ParameterInterface::TAG_ON_LOAD, FilesystemDataBridge::class);
di_tag(ParameterInterface::TAG_ON_PERSIST, FilesystemDataBridge::class);
di_tag(ParameterInterface::TAG_ON_REMOVE, FilesystemDataBridge::class);
di_tag(ParameterInterface::TAG_ON_FIND, FilesystemDataBridge::class);

di_set_param(\eArc\DataFilesystem\ParameterInterface::DATA_PATH, '/path/to/save/the/entity/data');
```

Hint: The first `ParameterInterface` belongs to earc/data library the second to 
earc/data-filesystem bridge.

Now earc/data uses your filesystem to save the data of your entities.

## releases

### release 0.0

* the first official release
* PHP ^8.0
