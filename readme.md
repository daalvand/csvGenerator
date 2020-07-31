# Overview
This package is suitable for creating csv output from nested arrays as well as fields that are themselves variable length arrays.
Here are some examples of how this package works ...
# Installation
Setup require in your projects composer.json file. Latest release: 
```shell script
composer require daalvand/csv-generator
``` 
# Usage
First import the Mapper and Generator classes and specify the file name and path.

```php
require "vendor/autoload.php";
$mapper    = new Daalvand\CsvGenerator\Mapper();
$generator = new Daalvand\CsvGenerator\Generator();
$filePath  = '/path';
$fileName  = 'file.csv';
$generator->setFileName($fileName)
          ->setFilePath($filePath);
```

#options
```php
//determine use utf8 and bom or not.  default -> false
$generator->setShouldAddBOM(false);
//set enclosure  (one character only) . default -> "
$generator->setEnclosure('"');
//set enclosure  (one character only). default -> ,
$generator->setDelimiter(',');
//check if file exists append rows to end of file. default -> false
$generator->shouldAppend(true);

```



Can see a sample data here:
```php
    $data = [
        [
            'id' => 1,
            'tags' => [
                ['id' => 1, 'name' => 'a'],
                ['id' => 2, 'name' => 'b'],
                ['id' => 3, 'name' => 'c']
            ],
            'user' => [
                'id' => 1,
                'username' => 'user1',
                'created_at' => '2020-10-10 10:10:10'
            ]
        ],
        [
            'id' => 2,
            'tags' => [
                ['id' => 1, 'name' => 'a'],
                ['id' => 3, 'name' => 'c']
            ],
            'user' => [
                'id' => 2,
                'username' => 'user2',
                'created_at' => '2020-11-11 11:11:11'
            ]
        ],
        [
            'id' => 3,
            'tags' => [],
            'user' => [
                'id' => 3,
                'username' => 'user3',
                'created_at' => '2020-12-12 12:12:12'
            ]
        ]
    ];
```


#first example:
The fastest way to create csv in this package is create headers from array structure by the dot based string
```php
$headers = [
    'id',
    'tags.*.id',
    'tags.*.name',
    'user.id',
    'user.username',
    'user.created_at',
];


$mapper->setHeaders($headers);
//add mapper to generator service and open service 
$generator->setMapper($mapper)->openGenerator();
// add rows
foreach ($data as $datum) {
    $generator->addRow($datum);
}
$generator->close();
```
#second example
in this example we can change name and change value of real data 
in below example `primary` header created from `id` field or `tags.ids` created from `tags.*.id`.
Also we can apply action to a field to change that value. for example: `user.created_at.time` is time of `user.created_at` date time field.

```php
$headers = [
    'primary'              => ['from' => 'id'],
    'tags.ids'             => ['from' => 'tags.*.id'],
    'tags.names'           => ['from' => 'tags.*.name'],
    'user.id'              => ['from' => 'user.id'],
    'user.username'        => ['from' => 'user.username'],
    'user.created_at.time' => ['from' => 'user.created_at', 'action' => 'NameSpace\TimeService@getTime'],
    'user.created_at.date' => ['from' => 'user.created_at', 'action' => 'NameSpace\TimeService@getDate']
];
$mapper->setHeaders($headers);
$generator->setMapper($mapper)->openGenerator();

foreach ($data as $datum) {
    $generator->addRow($datum);
}
$generator->close();
```