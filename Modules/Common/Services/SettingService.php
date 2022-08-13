<?php


namespace Modules\Common\Services;


use Modules\Common\Entities\SettingEntity;
use Modules\Common\Http\Resources\SettingCollection;
use Modules\Common\Http\Resources\SettingResource;
use Modules\Common\Http\Resources\UserCollection;
use Modules\Common\Models\SettingModel;
use Modules\Shared\Libraries\MainService;
use Modules\Shared\Libraries\UrlAggregation;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SettingService extends MainService
{
    private SettingModel $model;

    public function __construct()
    {        parent::__construct();
        $this->model = new  SettingModel();
    }


    public function index(UrlAggregation $urlAggregation)
    {

        $pipeLine = $urlAggregation->decodeQueryParam()->getPipeLine();

        $result = $this->model->aggregatePagination($pipeLine);

        return new SettingCollection($result);

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

        //  $result = $this->model->where('id', $id)->get();
        $result = new SettingResource($this->model->findOrFail($id));
        if (is_null($result)) throw new HttpException(ResponseAlias::HTTP_NOT_FOUND, __('api.commons.exist'));

        return [
            'data' => [$result],
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
