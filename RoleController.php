<?php
/**
 * Controlador de Gestión de Roles
 * CRM de Cobranzas - Gestión de roles y permisos
 */

require_once 'controllers/Controller.php';
require_once 'models/RoleModel.php';

class RoleController extends Controller {
    private $roleModel;
    
    public function __construct() {
        parent::__construct();
        $this->roleModel = new RoleModel();
    }
    
    /**
     * Listar todos los roles
     */
    public function index() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Gestión de Roles';
        $user = $this->user;
        
        // Obtener roles
        $roles = $this->roleModel->getAll();
        
        $this->render('roles/index', compact('title', 'user', 'roles'));
    }
    
    /**
     * Crear nuevo rol
     */
    public function create() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $this->getPost('name');
            $description = $this->getPost('description');
            
            if (empty($name)) {
                $this->redirectWithMessage('roles', 'El nombre del rol es requerido', 'error');
                return;
            }
            
            if ($this->roleModel->nameExists($name)) {
                $this->redirectWithMessage('roles', 'Ya existe un rol con ese nombre', 'error');
                return;
            }
            
            $data = [
                'name' => $name,
                'description' => $description
            ];
            
            if ($this->roleModel->create($data)) {
                $this->redirectWithMessage('roles', 'Rol creado exitosamente');
            } else {
                $this->redirectWithMessage('roles', 'Error al crear el rol', 'error');
            }
        } else {
            $title = 'Crear Rol';
            $user = $this->user;
            $this->render('roles/create', compact('title', 'user'));
        }
    }
    
    /**
     * Editar rol
     */
    public function update() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $description = $this->getPost('description');
            
            if (empty($id) || empty($name)) {
                $this->redirectWithMessage('roles', 'Datos requeridos faltantes', 'error');
                return;
            }
            
            if ($this->roleModel->nameExists($name, $id)) {
                $this->redirectWithMessage('roles', 'Ya existe un rol con ese nombre', 'error');
                return;
            }
            
            $data = [
                'name' => $name,
                'description' => $description
            ];
            
            if ($this->roleModel->update($id, $data)) {
                $this->redirectWithMessage('roles', 'Rol actualizado exitosamente');
            } else {
                $this->redirectWithMessage('roles', 'Error al actualizar el rol', 'error');
            }
        } else {
            $id = $this->getGet('id');
            if (empty($id)) {
                $this->redirectWithMessage('roles', 'ID de rol requerido', 'error');
                return;
            }
            
            $role = $this->roleModel->getById($id);
            if (!$role) {
                $this->redirectWithMessage('roles', 'Rol no encontrado', 'error');
                return;
            }
            
            $title = 'Editar Rol';
            $user = $this->user;
            $this->render('roles/update', compact('title', 'user', 'role'));
        }
    }
    
    /**
     * Eliminar rol
     */
    public function delete() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $id = $this->getPost('id');
        if (empty($id)) {
            $this->redirectWithMessage('roles', 'ID de rol requerido', 'error');
            return;
        }
        
        // Verificar si el rol está en uso
        if ($this->roleModel->isInUse($id)) {
            $this->redirectWithMessage('roles', 'No se puede eliminar el rol porque está en uso', 'error');
            return;
        }
        
        if ($this->roleModel->delete($id)) {
            $this->redirectWithMessage('roles', 'Rol eliminado exitosamente');
        } else {
            $this->redirectWithMessage('roles', 'Error al eliminar el rol', 'error');
        }
    }
}

