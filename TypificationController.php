<?php
/**
 * Controlador de Gestión de Tipificaciones
 * CRM de Cobranzas - Gestión de tipificaciones para asesores
 */

require_once 'controllers/Controller.php';
require_once 'models/TypificationModel.php';

class TypificationController extends Controller {
    private $typificationModel;
    
    public function __construct() {
        parent::__construct();
        $this->typificationModel = new TypificationModel();
    }
    
    /**
     * Listar todas las tipificaciones
     */
    public function index() {
        $this->requireAuth();
        // Permitir acceso a administradores y asesores
        if (!isAdmin() && !isAdvisor()) {
            $this->render('errors/403');
            return;
        }
        
        $title = 'Gestión de Tipificaciones';
        $user = $this->user;
        
        // Obtener tipificaciones con conteo de uso
        $typifications = $this->typificationModel->getWithUsageCount();
        
        // Obtener categorías para el modal de creación
        $categories = $this->typificationModel->getCategories();
        
        $this->render('typifications/index', compact('title', 'user', 'typifications', 'categories'));
    }
    
    /**
     * Crear nueva tipificación
     */
    public function create() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $name = $this->getPost('name');
            $code = $this->getPost('code');
            $category_id = $this->getPost('category_id');
            
            if (empty($name) || empty($code) || empty($category_id)) {
                $this->redirectWithMessage('typifications', 'Todos los campos son requeridos', 'error');
                return;
            }
            
            if ($this->typificationModel->nameExists($name)) {
                $this->redirectWithMessage('typifications', 'Ya existe una tipificación con ese nombre', 'error');
                return;
            }
            
            // Verificar si el código ya existe
            if ($this->typificationModel->codeExists($code)) {
                $this->redirectWithMessage('typifications', 'Ya existe una tipificación con ese código', 'error');
                return;
            }
            
            $data = [
                'name' => $name,
                'code' => $code,
                'category_id' => $category_id
            ];
            
            if ($this->typificationModel->create($data)) {
                $this->redirectWithMessage('typifications', 'Tipificación creada exitosamente');
            } else {
                $this->redirectWithMessage('typifications', 'Error al crear la tipificación', 'error');
            }
        } else {
            $title = 'Crear Tipificación';
            $user = $this->user;
            $categories = $this->typificationModel->getCategories();
            $this->render('typifications/create', compact('title', 'user', 'categories'));
        }
    }
    
    /**
     * Editar tipificación
     */
    public function update() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $id = $this->getPost('id');
            $name = $this->getPost('name');
            $code = $this->getPost('code');
            $category_id = $this->getPost('category_id');
            
            if (empty($id) || empty($name) || empty($code) || empty($category_id)) {
                $this->redirectWithMessage('typifications', 'Todos los campos son requeridos', 'error');
                return;
            }
            
            if ($this->typificationModel->nameExists($name, $id)) {
                $this->redirectWithMessage('typifications', 'Ya existe una tipificación con ese nombre', 'error');
                return;
            }
            
            // Verificar si el código ya existe (excluyendo el actual)
            if ($this->typificationModel->codeExists($code, $id)) {
                $this->redirectWithMessage('typifications', 'Ya existe una tipificación con ese código', 'error');
                return;
            }
            
            $data = [
                'name' => $name,
                'code' => $code,
                'category_id' => $category_id
            ];
            
            if ($this->typificationModel->update($id, $data)) {
                $this->redirectWithMessage('typifications', 'Tipificación actualizada exitosamente');
            } else {
                $this->redirectWithMessage('typifications', 'Error al actualizar la tipificación', 'error');
            }
        } else {
            $id = $this->getGet('id');
            if (empty($id)) {
                $this->redirectWithMessage('typifications', 'ID de tipificación requerido', 'error');
                return;
            }
            
            $typification = $this->typificationModel->getById($id);
            if (!$typification) {
                $this->redirectWithMessage('typifications', 'Tipificación no encontrada', 'error');
                return;
            }
            
            $title = 'Editar Tipificación';
            $user = $this->user;
            $categories = $this->typificationModel->getCategories();
            $this->render('typifications/update', compact('title', 'user', 'typification', 'categories'));
        }
    }
    
    /**
     * Eliminar tipificación
     */
    public function delete() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $id = $this->getPost('id');
        if (empty($id)) {
            $this->redirectWithMessage('typifications', 'ID de tipificación requerido', 'error');
            return;
        }
        
        // Verificar si la tipificación está en uso
        if ($this->typificationModel->isInUse($id)) {
            $this->redirectWithMessage('typifications', 'No se puede eliminar la tipificación porque está en uso', 'error');
            return;
        }
        
        if ($this->typificationModel->delete($id)) {
            $this->redirectWithMessage('typifications', 'Tipificación eliminada exitosamente');
        } else {
            $this->redirectWithMessage('typifications', 'Error al eliminar la tipificación', 'error');
        }
    }
    
    /**
     * Toggle estado de tipificación
     */
    public function toggle() {
        $this->requireAuth();
        if (!isAdmin()) {
            $this->render('errors/403');
            return;
        }
        
        $id = $this->getPost('id');
        if (empty($id)) {
            $this->redirectWithMessage('typifications', 'ID de tipificación requerido', 'error');
            return;
        }
        
        if ($this->typificationModel->toggleStatus($id)) {
            $this->redirectWithMessage('typifications', 'Estado de la tipificación actualizado exitosamente');
        } else {
            $this->redirectWithMessage('typifications', 'Error al actualizar el estado de la tipificación', 'error');
        }
    }
}

