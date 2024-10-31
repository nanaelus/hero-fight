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
            $avatars = model('MediaModel')->getAllAvatar();
            $characters = model('CharacterModel')->getAllCharacters();
            return $this->view('admin/character/index',['characters' => $characters, 'avatars' => $avatars], true);
        }
        $character = model('CharacterModel')->getCharacterById($id);
        if($id == "new") {
            $points = 10;
            return $this->view('admin/character/character', ['points' =>$points, "character" => $character], true);
        }
        if($id){
            $avatar = model('MediaModel')->getAvatarByIdCharacter($id, "character");
            return $this->view('admin/character/character', ['character' => $character, 'avatar' => $avatar], true);
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
                $this->redirect('admin/character');
            }

        }

        if(model('CharacterModel')->updateCharacter($data['id'], $data)) {
            $this->success("Le personnage a bien été modifié");
        } else {
            $this->error("Une erreur est survenue lors de la modification du personnage");
        }
        $this->redirect('admin/character');
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
