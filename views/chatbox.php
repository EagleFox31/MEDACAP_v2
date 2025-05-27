<?php
require '../vendor/autoload.php'; // Make sure to include Composer's autoload file

// MongoDB connection
$client = new MongoDB\Client("mongodb://localhost:27017");
$collection = $client->academy->chats;

// Check if request method is POST for saving messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!empty($data['userId']) && !empty($data['userName']) && !empty($data['message']) && !empty($data['timestamp']) && !empty($data['url']) && !empty($data['level'])) {
        $result = $collection->insertOne([
            'user' => new MongoDB\BSON\ObjectId($data['userId']),
            'message' => $data['message'],
            'qcm' => $data['url'],
            'status' => "Non traité",
            'level' => $data['level'], // Include the level field
            'active' => true,
            'created' => $data['timestamp']
        ]);

    //     echo json_encode(['success' => true, 'messageId' => $result->getInsertedId()]);
    //     try {
    //     } catch (Exception $e) {
    //         echo json_encode(['success' => false, 'message' => 'Failed to save message.']);
    //     }
    // } else {
    //     echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
    // exit();
}

// Check if request method is GET for loading messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['getMessages']) && $_GET['getMessages'] === 'true') {
        try {
            $cursor = $collection->find([], ['sort' => ['timestamp' => -1]]);
            $messages = [];

            foreach ($cursor as $document) {
                $messages[] = [
                    'user' => new MongoDB\BSON\ObjectId($document['user']),
                    'message' => $document['message'],
                    'qcm' => $document['qcm'],
                    'status' => $document['status'], // Include the level field
                    'level' => $document['level'], // Include the level field
                    'created' => $document['created']
                ];
            }

            echo json_encode(['success' => true, 'messages' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to retrieve messages.']);
        }
        exit();
    }
}

//echo json_encode(['success' => false, 'message' => 'Invalid request.']);
