<?php
require_once __DIR__ . '/../core/Controller.php';

class UserController extends Controller {
    private $user;

    public function __construct() {
        $this->user = $this->model('User');
    }

    // GET /api/users
    public function index() {
        $users = $this->user->getAll();
        $this->response(['data' => $users]);
    }

    // GET /api/users/{id}
    public function show($id) {
        $user = $this->user->find($id);
        if ($user) {
            $this->response(['data' => $user]);
        } else {
            $this->response(['message' => 'User not found'], 404);
        }
    }

    // POST /api/users
    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['name']) || !isset($input['email'])) {
            $this->response(['message' => 'Invalid input'], 400);
        }
        if ($this->user->create($input['name'], $input['email'])) {
            $this->response(['message' => 'User created successfully'], 201);
        } else {
            $this->response(['message' => 'Failed to create user'], 500);
        }
    }

    // PUT /api/users/{id}
    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['name']) || !isset($input['email'])) {
            $this->response(['message' => 'Invalid input'], 400);
        }
        if ($this->user->update($id, $input['name'], $input['email'])) {
            $this->response(['message' => 'User updated']);
        } else {
            $this->response(['message' => 'Update failed'], 500);
        }
    }

    // DELETE /api/users/{id}
    public function destroy($id) {
        if ($this->user->delete($id)) {
            $this->response(['message' => 'User deleted']);
        } else {
            $this->response(['message' => 'Delete failed'], 500);
        }
    }
}
