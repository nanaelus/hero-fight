<?php

namespace App\Models;

use CodeIgniter\Model;

class CharacterModel extends Model
{
    protected $table            = 'characters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'name', 'strength', 'constitution', 'agility', 'divided_points', 'experience', 'level', 'created_at', 'updated_at', 'deleted_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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


    public function createCharacter($data) {
//        print_r($data); die();
        return $this->insert($data);
    }

    public function updateCharacter($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteCharacter($id) {
        return $this->delete($id);
    }

    public function activateCharacter($id)
    {
        $builder = $this->builder();
        $builder->set('deleted_at', NULL);
        $builder->where('id', $id);
        return $builder->update();
    }

    public function getAllCharacters() {
        return $this->findAll();
    }

    public function getCharacterById($id) {
        return $this->find($id);
    }

    public function getCharactersByIdUser($id_user) {
        return $this->where('user_id', $id_user)->findAll();
    }

    public function getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection, $entity_type ="character", $custom_filter = null, $custom_filter_value = null)
    {
        $builder = $this->builder();
        $builder->select('characters.*, u.username, m.file_path as avatar_url');
        $builder->join('user u', 'u.id = characters.user_id', 'left');
        $builder->join('media m','m.entity_id = characters.id AND m.entity_type = "character"', 'left');
        if($custom_filter) {
            switch ($custom_filter) {
                case "character" :
                    $builder->where('characters.user_id', $custom_filter_value);
                    break;
                case 'user' :
                    $builder->where('characters.user_id', $custom_filter_value);
                    $builder->where('characters.deleted_at IS NULL');
                    break;
            }
        }

        // Recherche
        if ($searchValue != null) {
            $builder->like('name', $searchValue);
            $builder->orLike('id', $searchValue);
            $builder->orLike('username', $searchValue);
            $builder->orLike('level', $searchValue);
        }

        // Tri
        if ($orderColumnName && $orderDirection) {
            $builder->orderBy($orderColumnName, $orderDirection);
        }

        $builder->limit($length, $start);

        return $builder->get()->getResultArray();
    }

    public function getTotal($entity_type ="character", $custom_filter = null, $custom_filter_value = null)
    {
        $builder = $this->builder();
        $builder->select('characters.*, u.username, m.file_path as avatar_url');
        $builder->join('user u', 'u.id = characters.user_id', 'left');
        $builder->join('media m','m.entity_id = characters.id AND m.entity_type = "character"', 'left');
        if($custom_filter) {
            switch ($custom_filter) {
                case "character" :
                    $builder->where('characters.user_id', $custom_filter_value);
                    break;
                case 'user' :
                    $builder->where('characters.user_id', $custom_filter_value);
                    $builder->where('characters.deleted_at IS NULL');
                    break;
            }
        }
        return $builder->countAllResults();
    }

    public function getFiltered($searchValue, $entity_type ="character", $custom_filter = null, $custom_filter_value = null)
    {
        $builder = $this->builder();
        $builder->select('characters.*, u.username, m.file_path as avatar_url');
        $builder->join('user u', 'u.id = characters.user_id', 'left');
        $builder->join('media m','m.entity_id = characters.id AND m.entity_type = "character"', 'left');
        if($custom_filter) {
            switch ($custom_filter) {
                case "character" :
                    $builder->where('characters.user_id', $custom_filter_value);
                    break;
                case 'user' :
                    $builder->where('characters.user_id', $custom_filter_value);
                    $builder->where('characters.deleted_at IS NULL');
                    break;
            }
        }

        if (!empty($searchValue)) {
            $builder->like('name', $searchValue);
            $builder->orLike('id', $searchValue);
            $builder->orLike('username', $searchValue);
            $builder->orLike('level', $searchValue);
        }
        return $builder->countAllResults();
    }
}