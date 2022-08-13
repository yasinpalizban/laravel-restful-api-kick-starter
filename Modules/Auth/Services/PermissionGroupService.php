<?php


namespace Modules\Auth\Services;


use Modules\Auth\Models\PermissionGroupModel;
use Modules\Common\Entities\SettingEntity;
use Modules\Shared\Libraries\MainService;
use Modules\Shared\Libraries\UrlAggregation;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionGroupService extends MainService
{
    private PermissionGroupModel $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new  PermissionGroupModel();

    }


    public function index(UrlAggregation $urlAggregation)
    {
        $pipeLine = ['select' => '
        auth_groups_permissions.id,
        auth_groups_permissions.actions,
         auth_groups_permissions.group_id as groupId ,
         auth_groups_permissions.permission_id as permissionId ,
       auth_groups.name as group,
       auth_permissions.name as permission',
            'join' => [
                ['table' => 'auth_groups',
                    'arg1' => 'auth_groups.id',
                    'arg2' => 'auth_groups_permissions.group_id',
                    'condition' => '=',
                    'mode' => 'left'],
                ['table' => 'auth_permissions',
                    'arg1' => 'auth_permissions.id',
                    'arg2' => 'auth_groups_permissions.permission_id',
                    'condition' => '=',
                    'mode' => 'left']
            ]
        ];

        if($this->nestId!=0){
            $pipeLine['where']=[['auth_groups_permissions.permission_id','=',$this->nestId]];
        }

        $pipeLine = $urlAggregation->setTableName('auth_groups_permissions')
            ->decodeQueryParam()
            ->getPipeLine($pipeLine);
        $data = $this->model->aggregatePagination($pipeLine);
        $urlAggregation->clearPipeLine();

        return $data;
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
        select(['auth_groups_permissions.id',
            'auth_groups_permissions.actions',
            'auth_groups_permissions.group_id as groupId',
            'auth_groups_permissions.permission_id as permissionId',
            'auth_groups.name as group',
            'auth_permissions.name as permission',])->
        leftJoin('auth_groups', 'auth_groups.id', '=', 'auth_groups_permissions.group_id')->
        leftJoin('auth_permissions', 'auth_permissions.id', '=', 'auth_groups_permissions.permission_id')->


        where('auth_groups_permissions.id', $id)->get();

        if (is_null($result)) throw new HttpException(ResponseAlias::HTTP_NOT_FOUND, __('api.commons.exist'));

        return [
            'data' => $result,
        ];

    }


    public function create(SettingEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));


        if (!$this->model->create($entity->getArray())) {

            throw new HttpException(ResponseAlias::HTTP_BAD_REQUEST, __('Shared.api.reject'));

        }


    }


    public function update($id, SettingEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.commons.reject'));

        $this->model->where('id', $id)->update($entity->getArray());

    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     */
    public function delete($id)
    {

        $deleteById = $this->model->find($id);

        if (is_null($deleteById)) throw new HttpException(ResponseAlias::HTTP_NOT_FOUND, __('api.commons.reject'));

        $this->model->destroy($id);


    }

    public function getInsertId()
    {
        return $this->model->latest('id')->first()->id;
    }
}
