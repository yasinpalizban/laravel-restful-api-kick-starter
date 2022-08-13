<?php


namespace Modules\Common\Services;



use Modules\Auth\Models\GroupModel;
use Modules\Auth\Models\UserModel;

use Modules\Common\Entities\UserEntity;
use Modules\Common\Http\Resources\UserResource;
use Modules\Shared\Libraries\MainService;
use Modules\Shared\Libraries\UrlAggregation;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService extends  MainService
{
    private  UserModel $model;
    private  GroupModel $groupModel;

    public function __construct()
    {        parent::__construct();
        $this->model = new  UserModel();
        $this->groupModel=  new GroupModel();

    }


    public function index(UrlAggregation $urlAggregation)
    {

        $pipeLine = [
            'select' => '
            users.id,
      users.email,
      users.username ,
      users.first_name as firstName,
      users.last_name as lastName,
      users.image,
      users.gender,
      users.birthday,
      users.country,
      users.city,
      users.address,
      users.phone,
      users.status_message as statusMessage,
      users.status,
      users.active ,
      users.created_at as createdAt,
      users.updated_at as updatedAt,
      users.deleted_at as deletedAt',
            'auth_groups.name as group',
            'join' => [
                ['table' => 'auth_groups_users',
                    'arg1' => 'auth_groups_users.group_id',
                    'arg2' => 'auth_groups.id',
                    'condition' =>'=',
                    'mode' => 'right'],
                ['table' => 'users',
                    'arg1' => 'auth_groups_users.user_id',
                    'arg2' => 'users.id',
                    'condition' => '=',
                    'mode' => 'left']
            ]
        ];
        $pipeLine = $urlAggregation->setTableName('users')->decodeQueryParam()->getPipeLine($pipeLine);


    $data=$this->groupModel->aggregatePagination($pipeLine);
      $urlAggregation->clearPipeLine();

     return   $data;
    }

    /**
     * show function
     * @method : GET with params ID
     * @param $id
     * @return array
     */
    public function show($id)
    {
        if (is_null($id)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));

     //   $result = $this->model->where('id', $id)->get();

        $result = new UserResource($this->model->findOrFail($id));
        if (is_null($result)) throw new HttpException( ResponseAlias::HTTP_NOT_FOUND,__('api.commons.exist'));

        return [
            'data' => [$result],
        ];

    }


    public function create(UserEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));


        if (!$this->model->create($entity->getArray())) {

            throw new HttpException( ResponseAlias::HTTP_BAD_REQUEST,__('Shared.api.reject'));

        }



    }


    public function update($id , UserEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));

        $this->model->where('id',$id)->update( $entity->getArray());

    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     */
    public function delete($id )
    {

        $deleteById = $this->model->find($id);

        if (is_null($deleteById)) throw new HttpException(ResponseAlias::HTTP_NOT_FOUND,__('api.commons.reject'));

        $this->model->destroy($id);


    }
    public function getInsertId()
    {
        return $this->model->latest('id')->first()->id;
    }

    public function getCountItems()
    {
        return $this->model->count();
    }
}
