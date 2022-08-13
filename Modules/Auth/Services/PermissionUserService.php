<?php


namespace Modules\Auth\Services;



use Modules\Auth\Entities\PermissionUserEntity;
use Modules\Auth\Models\PermissionUserModel;

use Modules\Shared\Libraries\MainService;
use Modules\Shared\Libraries\UrlAggregation;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionUserService extends  MainService
{
    private  PermissionUserModel $model;

    public function __construct()
    {        parent::__construct();
        $this->model = new  PermissionUserModel();
    }


    public function index(UrlAggregation $urlAggregation)
    {

        $pipeLine = [
            'select' => 'auth_users_permissions.id,
        auth_users_permissions.actions,
         auth_users_permissions.user_id as userId ,
         auth_users_permissions.permission_id as permissionId ,
        users.username,
        users.first_name as firstName,
        users.last_name as lastName,
       auth_permissions.name as permission',
            'join' => [
                ['table' => 'users',
                    'arg1' => 'users.id',
                    'arg2' => 'auth_users_permissions.user_id',
                    'condition' => '=',
                    'mode' => 'left'],
                ['table' => 'auth_permissions',
                    'arg1' => 'auth_permissions.id',
                    'arg2' => 'auth_users_permissions.permission_id',
                    'condition' => '=',
                    'mode' => 'left']
            ]
        ];

        if($this->nestId!=0){
            $pipeLine['where']=[['auth_users_permissions.permission_id','=',$this->nestId]];
        }

        $pipeLine = $urlAggregation->setTableName('auth_users_permissions')
            ->decodeQueryParam()
            ->getPipeLine($pipeLine);

        $data= $this->model->aggregatePagination($pipeLine);
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

        $result = $this->model->
            select(['auth_users_permissions.id',
        'auth_users_permissions.actions',
         'auth_users_permissions.user_id as userId' ,
        'auth_users_permissions.permission_id as permissionId' ,
        'users.username',
        'users.first_name as firstName',
        'users.last_name as lastName',
      'auth_permissions.name as permission'])->
             leftJoin('auth_permissions','auth_permissions.id','=','auth_users_permissions.permission_id')->
             leftJoin('users','users.id','=','auth_users_permissions.user_id')

                 ->where('auth_users_permissions.id', $id)->get();

        if (is_null($result)) throw new HttpException( ResponseAlias::HTTP_NOT_FOUND,__('api.commons.exist'));

        return [
            'data' => $result,
        ];

    }


    public function create(PermissionUserEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));


        if (!$this->model->create($entity->getArray())) {

            throw new HttpException( ResponseAlias::HTTP_BAD_REQUEST,__('Shared.api.reject'));

        }



    }


    public function update($id , PermissionUserEntity $entity)
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
}
