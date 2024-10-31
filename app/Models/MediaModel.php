<?php

namespace App\Models;

use CodeIgniter\Model;

class MediaModel extends Model
{
    protected $table            = 'media';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['file_path','mime_type', 'entity_id', 'entity_type', 'created_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllAvatar() {
     return $this->findAll();
    }

    public function getAllMedias($limit = null, $offset = 0) {
        return $this->findAll($limit, $offset);
    }
    public function getAvatarByIdCharacter($id, $entity_type) {
        return $this->where('entity_type', $entity_type)->where('entity_id', $id)->findAll();
    }

    public function getMediaByEntityIdAndType($entityId,$entityType) {
        return $this->where('entity_type', $entityType)->where('entity_id', $entityId)->findAll();
    }


    public function deleteMedia($id) {
        // Récupérer les informations du fichier depuis la base de données
        $fichier = $this->find($id);
        if ($fichier) {
            // Chemin complet du fichier tel qu'il est stocké dans la base de données
            $chemin = FCPATH . $fichier['file_path'];

            // Vérifier si le fichier existe et le supprimer
            if (file_exists($chemin)) {
                // Supprimer le fichier physique
                unlink($chemin);
                // Supprimer l'entrée de la base de données
                return $this->delete($id);
            }
        }
        return false; // Le fichier n'a pas été trouvé
    }
}
