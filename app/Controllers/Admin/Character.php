<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Character extends BaseController
{
    protected $requiredPermissions = ['administrateur'];
    protected $require_auth = true;

    public function getIndex($id=null)
    {
        if($id == null) {
            $characters = model('CharacterModel')->getAllCharacters();
            return $this->view('admin/character/index',['characters' => $characters], true);
        }
        if($id == "new") {
            $points = 10;
            return $this->view('admin/character/character', ['points' =>$points], true);
        }
        if($id){
            return $this->view('admin/character/character', [], true);
        }
    }

    public function postcreatecharacter(){
        $data = $this->request->getPost();
        $cm = model('CharacterModel');

        $newCharacterId = $cm->createCharacter($data);
        if($newCharacterId) {
            $file = $this->request->getFile('profile_image');
            if($file && $file->isValid()) {
                $mediaData = [
                    'entity_type' => 'character',
                    'entity_id' => $newCharacterId,
                    ];

                $filePath = upload_file($file, 'avatar', $data['name'], $mediaData);

                if($filePath === false) {
                    $this->error('Une erreur est survenue lors de l\'upload de l\'image');
                    $this->redirect(base_url('admin/character/new'));
                }
            }
            $this->success('Personnage créé avec succès!');
            $this->redirect('admin/character');
        } else {
            $this->error('Erreur lors de la création du personnage');
            $this->redirect('admin/character/new');
        }
    }

    public function postsearchdatatable()
    {
        $model_name = $this->request->getPost('model');
        $model = model($model_name);
        // Paramètres de pagination et de recherche envoyés par DataTables
        $draw        = $this->request->getPost('draw');
        $start       = $this->request->getPost('start');
        $length      = $this->request->getPost('length');
        $searchValue = $this->request->getPost('search')['value'];


        // Obtenez les informations sur le tri envoyées par DataTables
        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDirection = $this->request->getPost('order')[0]['dir'] ?? 'asc';
        $orderColumnName = $this->request->getPost('columns')[$orderColumnIndex]['data'] ?? 'id';

        // Obtenez les données triées et filtrées
        $data = $model->getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection);

        // Obtenez le nombre total de lignes sans filtre
        $totalRecords = $model->getTotal();

        // Obtenez le nombre total de lignes filtrées pour la recherche
        $filteredRecords = $model->getFiltered($searchValue);

        $result = [
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ];
        return $this->response->setJSON($result);
    }

    public function getdesactivate($id) {
        if(model('CharacterModel')->deleteCharacter($id)) {
            $this->success('Personnage Désactivé');
        } else {
            $this->error('Erreur lors de la désactivation du personnage');
        }
        $this->redirect('admin/character');
    }

    public function getactivate($id) {
        if(model('CharacterModel')->activateCharacter($id)) {
            $this->success('Personnage activé');
        } else {
            $this->error('Erreur lors de l\'activation du personnage');
        }
        $this->redirect('admin/character');
    }
}
