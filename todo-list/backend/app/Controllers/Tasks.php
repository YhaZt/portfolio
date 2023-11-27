<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\TaskModel;

class Tasks extends ResourceController
{
    // protected $taskModel;

    // public function __construct()
    // {
    //     $this->taskModel = new TaskModel();
    // }
    public function index()
    {
        $firebaseConfig = config('Firebase');
        $serviceAccountPath = APPPATH . 'Config/todo-list-bafbc-firebase-adminsdk-p9rki-22d3e48bfe.json';
        if (!file_exists($serviceAccountPath)) {
            log_message('error', 'Service account JSON file not found.');
            return $this->response->setJSON(['error' => 'Service account JSON file not found']);
        }
        try {
            $firebase = (new \Kreait\Firebase\Factory())
                ->withServiceAccount($serviceAccountPath)
                ->withDatabaseUri($firebaseConfig->databaseURL)
                ->createDatabase();
        } catch (\Exception $e) {
            log_message('error', 'Firebase initialization error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Firebase initialization error']);
        }
        if (!$firebase) {
            log_message('error', 'Firebase connection failed.');
            return $this->response->setJSON(['error' => 'Firebase connection failed']);
        }
        // return $this->response->setJSON(['message' => 'Firebase connection successful']);
        
        $tasksReference = $firebase->getReference('/tasks');

        try {
            $tasks = $tasksReference->getValue();

            if ($tasks) {
                return $this->respond($tasks);
            } else {
                return $this->respond([]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->fail(['error' => 'Error retrieving tasks'], 500);
        }
    }
    
    public function create()
    {
        $title = $this->request->getPost('title');
        $description = $this->request->getPost('description');
        $firebaseConfig = config('Firebase');
        $serviceAccountPath = APPPATH . 'Config/todo-list-bafbc-firebase-adminsdk-p9rki-22d3e48bfe.json';
        if (!file_exists($serviceAccountPath)) {
            log_message('error', 'Service account JSON file not found.');
            return $this->response->setJSON(['error' => 'Service account JSON file not found']);
        }
        try {
            $firebase = (new \Kreait\Firebase\Factory())
                ->withServiceAccount($serviceAccountPath)
                ->withDatabaseUri($firebaseConfig->databaseURL)
                ->createDatabase();
        } catch (\Exception $e) {
            log_message('error', 'Firebase initialization error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Firebase initialization error']);
        }
        $tasksReference = $firebase->getReference('/tasks');

        try {
            $newTaskKey = $tasksReference->push([
                'title' => $title,
                'description' => $description,
            ])->getKey();

            // Optionally, you can store the new task in your local database
            // $data = [
            //     'title' => $title,
            //     'description' => $description,
            //     'firebase_key' => $newTaskKey,
            // ];
            // $this->taskModel->insert($data);

            return $this->response->setJSON(['message' => 'Task created successfully']);
        } catch (\Exception $e) {
            log_message('error', 'Exception: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Error creating task']);
        }
    }
}
