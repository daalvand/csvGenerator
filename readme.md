#example for csv generator by mapping
```php
$mapping = [
    'id' => ['type' => 'string'],
    'tags' => [
        'type' => 'array',
        'items' => [
            'id' => [
                'type' => 'integer'
            ],
            'name' => [
                'type' => 'integer'
            ],
        ],
    ],
    'user' => [
        'type' => 'object',
        'items' => [
            'id' => ['type' => 'integer'],
            'username' => ['type' => 'string'],
            'created_at' => ['type' => 'date-time'],
        ]
    ]
];
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
$mapper = new Daalvand\CsvGenerator\Mapper();
$mapper
    ->shouldSplitDates(true)
    ->setMappings($mapping);

$csv = new Daalvand\CsvGenerator\Service();
$csv
    ->setMapper($mapper)
    ->setFileName('test.csv')
    ->setFilePath('.')
    ->openGenerator();

foreach ($data as $datum) {
    $csv->addRow($datum);
}
$csv->close();
```
#example by headers
```php
$headers = [
    'id'                   => ['from' => 'id'],
    'tags.ids'             => ['from' => 'tags.*.id'],
    'tags.names'           => ['from' => 'tags.*.name'],
    'user.id'              => ['from' => 'user.id'],
    'user.username'        => ['from' => 'user.username'],
    'user.created_at.time' => ['from' => 'user.created_at', 'action' => 'Daalvand\CsvGenerator\TimeService@getTime'],
    'user.created_at.date' => ['from' => 'user.created_at', 'action' => 'Daalvand\CsvGenerator\TimeService@getDate']
];
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
$mapper = new Daalvand\CsvGenerator\Mapper();
$mapper->setHeaders($headers);

$csv = new Daalvand\CsvGenerator\Service();
$csv
    ->setMapper($mapper)
    ->setFileName('test.csv')
    ->setFilePath('.')
    ->openGenerator();

foreach ($data as $datum) {
    $csv->addRow($datum);
}
$csv->close();
```



#simple example by headers
```php
$headers = [
    'id',
    'tags.*.id',
    'tags.*.name',
    'user.id',
    'user.username',
    'user.created_at',
];
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
$mapper = new Daalvand\CsvGenerator\Mapper();
$mapper->setHeaders($headers);

$csv = new Daalvand\CsvGenerator\Service();
$csv
    ->setMapper($mapper)
    ->setFileName('test.csv')
    ->setFilePath('.')
    ->openGenerator();

foreach ($data as $datum) {
    $csv->addRow($datum);
}
$csv->close();
```