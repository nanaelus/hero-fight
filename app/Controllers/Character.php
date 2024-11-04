<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CharacterModel;
use CodeIgniter\HTTP\ResponseInterface;

class Character extends BaseController
{
    protected $require_auth = true;
    protected $requiredPermissions = ['administrateur', 'collaborateur', 'utilisateur'];
    public function getindex($id = null)
    {
        $myCharacters = model('CharacterModel')->getCharactersByIdUser($this->session->user->id);
//        print_r($myCharacters); die();
        if ($id == null) {
            return $this->view('character/index', ['myCharacters' => $myCharacters]);
        }
        $character = model('CharacterModel')->getCharacterById($id);
        $userId = $this->session->user->id;
        if($id == "new") {
            return $this->view('character/character', ['character' => $character, 'userId' => $userId]);
        }
        if($id == $this->session->user->id) {
            $avatar = model('MediaModel')->getAvatarByIdCharacter($id, "character");
            return $this->view('character/character', ['character' => $character, 'avatar' => $avatar, 'userId' => $userId]);
        }
        if($id != $this->session->user->id) {
            $this->error("C'est n'est pas à toi!");
            return $this->view('character/index', ['myCharacters' => $myCharacters]);        }
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
                    $this->redirect(base_url('character/new'));
                }
            }
            $this->success('Personnage créé avec succès!');
            $this->redirect('character');
        } else {
            $this->error('Erreur lors de la création du personnage');
            $this->redirect('character/new');
        }
    }

    public function postupdatecharacter() {
        $data = $this->request->getPost();
        $file = $this->request->getFile('profile_image');
        if($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
//            print_r($data); die();
            $old_media = model('MediaModel')->getAvatarByIdCharacter($data['id'], "character");
            if($old_media) {
                model('MediaModel')->deleteMedia($old_media[0]['id']);
            }

            $mediaData = [
                'entity_type' => "character",
                'entity_id' => $data['id'],
            ];

            $uploadResult = upload_file($file, 'avatar', $data['name'], $mediaData, false, ['image/jpeg', 'image/png','image/jpg']);

            if(is_array($uploadResult) && $uploadResult['status'] ==="error") {
                $this->error("Une erreur est survenue lors de l'upload du fichier");
                $this->redirect('character');
            }

        }

        if(model('CharacterModel')->updateCharacter($data['id'], $data)) {
            $this->success("Le personnage a bien été modifié");
        } else {
            $this->error("Une erreur est survenue lors de la modification du personnage");
        }
        $this->redirect('character');
    }

    public function getdesactivate($id) {
        if(model('CharacterModel')->deleteCharacter($id)) {
            $this->success('Personnage Supprimé');
        } else {
            $this->error('Erreur lors de la désactivation du personnage');
        }
        $this->redirect('character');
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

        $type = $this->request->getPost('type') ?? 'character';
        $custom_filter = $this->request->getPost('filter') ?? null;
        $custom_filter_value = $this->request->getPost('filter_value') ?? null;

        // Obtenez les informations sur le tri envoyées par DataTables
        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDirection = $this->request->getPost('order')[0]['dir'] ?? 'asc';
        $orderColumnName = $this->request->getPost('columns')[$orderColumnIndex]['data'] ?? 'id';

        // Obtenez les données triées et filtrées
        $data = $model->getPaginated($start, $length, $searchValue, $orderColumnName, $orderDirection,$type, $custom_filter, $custom_filter_value);

        // Obtenez le nombre total de lignes sans filtre
        $totalRecords = $model->getTotal($type, $custom_filter, $custom_filter_value);

        // Obtenez le nombre total de lignes filtrées pour la recherche
        $filteredRecords = $model->getFiltered($searchValue,$type, $custom_filter, $custom_filter_value);

        $result = [
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $data,
        ];
        return $this->response->setJSON($result);
    }
}
