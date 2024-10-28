<?php

namespace App\Models;

use CodeIgniter\Model;

class CharacterModel extends Model
{
    protected $table            = 'character';
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

    public function getAllCharacters() {
        return $this->findAll();
    }

    public function createCharacter($data) {
        return $this->insert($data);
    }

    public function getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection)
    {
        $builder = $this->builder();
        $builder->select('character.*, u.username');
        $builder->join('user u', 'u.id = character.user_id', 'left');

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

    public function getTotal()
    {
        $builder = $this->builder();
        $builder->select('*');
        return $builder->countAllResults();
    }

    public function getFiltered($searchValue)
    {
        $builder = $this->builder();
        $builder->select('character.*, u.username');
        $builder->join('user u', 'u.id = character.user_id', 'left');

        if (!empty($searchValue)) {
            $builder->like('name', $searchValue);
            $builder->orLike('id', $searchValue);
            $builder->orLike('username', $searchValue);
            $builder->orLike('level', $searchValue);
        }
        return $builder->countAllResults();
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
}