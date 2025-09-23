<?php 

namespace App\Controllers;

abstract class Controller {
    protected $model;
    protected $data;

    public function __construct($model) {
        $this->model = $model;
    }

    public function selectAll() {
        return echo json_encode($this->model::all()); 
    
    }

    public function select() {
        $this->data = json_decode(file_get_contents("php://input"), true);
        return echo json_encode($this->model::find($this->data['id']));
    }

    public function delete() {
        $this->data = json_decode(file_get_contents("php://input"), true);

        $id = $this->data['id'] ?? null;

        if (!$id) {
            return ['success' => false, 'message' => 'No ID provided'];
        }

        $deleted = $this->model::delete($id);

        return $deleted
            ? echo json_encode(['success' => true, 'message' => 'Deleted successfully'])
            : echo json_encode(['success' => false, 'message' => 'Delete failed']);
    }
}