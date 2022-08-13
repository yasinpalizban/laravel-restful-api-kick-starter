<?php


namespace Modules\Common\Services;


use Illuminate\Support\Facades\Storage;
use Modules\Auth\Models\UserModel;
use Modules\Common\Entities\SettingEntity;
use Modules\Common\Entities\UserEntity;
use Modules\Common\Http\Resources\ProfileResource;
use Modules\Shared\Libraries\MainService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileService extends MainService
{
    private UserModel $model;

    public function __construct()
    {        parent::__construct();
        $this->model = new  UserModel();
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

        $result = $this->model->findOrFail($id);

        if (is_null($result)) throw new HttpException(ResponseAlias::HTTP_NOT_FOUND, __('api.commons.exist'));

        return ['data' => new ProfileResource($result)];

    }


    public function update($id, UserEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(ResponseAlias::HTTP_CONFLICT, __('api.validation'));
        $userData = $this->model->where('id', $id)->get();
        Storage::delete($userData[0]->image);

        $this->model->where('id', $id)->update($entity->getArray());


    }


}
