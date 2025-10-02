<?php 

namespace App\Controllers;

/**
 * Abstract base controller providing basic CRUD operations.
 */
abstract class Controller {

    protected $model;
    protected $data;

    /**
     * Constructor for the controller.
     *
     * @param string $model The model class this controller manages
     */
    public function __construct($model) {
        $this->model = $model;
    }

    /**
     * Retrieve all records from the model.
     */
    public function selectAll() {
        echo json_encode($this->model::all()); 
    }

    /**
     * Retrieve a single record by ID from the request body.
     */
    public function select() {
        $this->data = json_decode(file_get_contents("php://input"), true);
        echo json_encode($this->model::find($this->data['id']));
    }

    /**
     * Delete a record by ID from the request body.
     */
    public function delete() {
        $this->data = json_decode(file_get_contents("php://input"), true);

        $id = $this->data['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No ID provided']);
            return;
        }

        $deleted = $this->model::delete($id);

        if ($deleted) {
            echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Delete failed']);
        }
    }
}
