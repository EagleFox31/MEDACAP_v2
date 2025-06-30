<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Infrastructure\Mongo\ExportTaskRepository;
use Domain\Model\ExportTask;
use MongoDB\BSON\ObjectId;

class FakeCollection {
    public $docs = [];
    public function find($filter) {
        $result = [];
        foreach ($this->docs as $doc) {
            $match = true;
            foreach ($filter as $k => $v) {
                if ($doc[$k] instanceof ObjectId && $v instanceof ObjectId) {
                    if ((string)$doc[$k] !== (string)$v) { $match = false; break; }
                } else {
                    if ($doc[$k] !== $v) { $match = false; break; }
                }
            }
            if ($match) $result[] = $doc;
        }
        return new ArrayIterator($result);
    }
    public function insertOne($data) { $this->docs[] = $data; }
}

class FakeDatabase {
    public $collection;
    public function __construct() { $this->collection = new FakeCollection(); }
    public function selectCollection($name) { return $this->collection; }
}

class FakeMongoConnection {
    private $db;
    public function __construct() { $this->db = new FakeDatabase(); }
    public function getDatabase() { return $this->db; }
}

$connection = new FakeMongoConnection();
$repo = new ExportTaskRepository($connection);

$userId = new ObjectId();
$task = new ExportTask(null, (string)$userId, 'admin', 'type', 'first');
$repo->save($task);

$other = new ExportTask(null, (string)new ObjectId(), 'admin', 'type', 'second');
$repo->save($other);

$result = $repo->findByUser((string)$userId);

if (count($result) === 1 && $result[0]->getDescription() === 'first') {
    echo "Test passed\n";
    exit(0);
}

echo "Test failed\n";
exit(1);
