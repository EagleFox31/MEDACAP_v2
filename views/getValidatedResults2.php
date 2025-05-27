<?php
// getTechnicianResults.php

require_once "../vendor/autoload.php"; // Load necessary dependencies

use MongoDB\Client;


/**
 * Retrieves questions and answers for technicians and managers for a specific level.
 *
 * @param string $selectedLevel The selected level (Junior, Senior, Expert).
 * @return array An associative array where each key is the technician's ID, and each value is an associative array of questions and their statuses.
 */
function getTechnicianResults($selectedLevel = "Junior") {
    // Connect to MongoDB
    try {
        $conn = new Client("mongodb://localhost:27017");
    } catch (Exception $e) {
        die("Error connecting to MongoDB: " . $e->getMessage());
    }

    $academy = $conn->academy;
    $resultsCollection = $academy->results;

    // Fetch technicians' answers
    $technicianCursor = $resultsCollection->find([
        'typeR' => 'Techniciens',
        'type' => 'Declaratif',
        'level' => $selectedLevel,
        'active' => true
    ]);

    // Fetch managers' answers
    $managerCursor = $resultsCollection->find([
        'typeR' => 'Manager',
        'type' => 'Declaratif',
        'level' => $selectedLevel,
        'active' => true
    ]);

    $technicianResults = [];
    $managerResults = [];

    // Process technicians' answers
    foreach ($technicianCursor as $result) {
        if (!isset($result['user'])) {
            continue;
        }

        $techId = (string)$result['user'];

        if (!isset($technicianResults[$techId])) {
            $technicianResults[$techId] = [];
        }

        if (isset($result['questions']) && isset($result['answers'])) {
            $questionsArray = (array) $result['questions'];
            $answersArray = (array) $result['answers'];

            $totalQuestions = count($questionsArray);
            $totalAnswers = count($answersArray);

            if ($totalQuestions !== $totalAnswers) {
                continue;
            }

            foreach ($questionsArray as $index => $questionId) {
                $questionIdStr = (string)$questionId;
                $answerRaw = isset($answersArray[$index]) ? $answersArray[$index] : '';
                $answer = strtolower(trim($answerRaw));

                $technicianResults[$techId][$questionIdStr] = $answer;
            }
        }
    }

    // Process managers' answers
    foreach ($managerCursor as $result) {
        if (!isset($result['user'])) {
            continue;
        }

        $techId = (string)$result['user']; // This refers to the technician evaluated by the manager

        if (!isset($managerResults[$techId])) {
            $managerResults[$techId] = [];
        }

        if (isset($result['questions']) && isset($result['answers'])) {
            $questionsArray = (array) $result['questions'];
            $answersArray = (array) $result['answers'];

            $totalQuestions = count($questionsArray);
            $totalAnswers = count($answersArray);

            if ($totalQuestions !== $totalAnswers) {
                continue;
            }

            foreach ($questionsArray as $index => $questionId) {
                $questionIdStr = (string)$questionId;
                $answerRaw = isset($answersArray[$index]) ? $answersArray[$index] : '';
                $answer = strtolower(trim($answerRaw));

                $managerResults[$techId][$questionIdStr] = $answer;
            }
        }
    }

    // Combine technician and manager results
    $combinedResults = [];

    foreach ($technicianResults as $techId => $questions) {
        $combinedResults[$techId] = [];

        foreach ($questions as $questionId => $techAnswer) {
            // Default status
            $status = '-';

            // Technician's answer
            if ($techAnswer === 'oui' || $techAnswer === 'non') {
                $techAnswerBinary = ($techAnswer === 'oui') ? 1 : 0;
            } else {
                $techAnswerBinary = '-';
            }

            // Manager's answer
            if (isset($managerResults[$techId][$questionId])) {
                $mgrAnswer = $managerResults[$techId][$questionId];
                if ($mgrAnswer === 'oui' || $mgrAnswer === 'non') {
                    $mgrAnswerBinary = ($mgrAnswer === 'oui') ? 1 : 0;
                } else {
                    $mgrAnswerBinary = '-';
                }
            } else {
                $mgrAnswerBinary = null; // Manager hasn't evaluated yet
            }

            // Determine the status based on both answers
            if ($mgrAnswerBinary === null) {
                $status = 'À Noter';
            } else {
                if ($mgrAnswerBinary === 1 && $techAnswerBinary === 1) {
                    $status = 1;
                } elseif ($mgrAnswerBinary === 0) {
                    $status = 0;
                } else {
                    $status = '-';
                }
            }

            $combinedResults[$techId][$questionId] = $status;
        }
    }

    return $combinedResults;
}
?>
