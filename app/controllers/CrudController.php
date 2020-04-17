<?php


namespace App\Controller;

use App\Constant\ErrorCode;
use App\Constant\ModelMapping;
use App\Constant\RequestModel;
use App\Extension\Extensions;
use App\Interfaces\IDbContext;
use App\ViewModel\ViewModel;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;


class CrudController extends BaseController
{
    /**
     * @var IDbContext
     */
    protected $dbContext;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var string
     */
    protected $entityType;


    /**
     * CrudController constructor.
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->entityType = "Entity";
        $this->Message = "{$this->entityType} processed";
        $this->model = StdClass::class;
        $this->dbContext = null;
    }

    /**
     * @param Request $rq instance of the request
     * @return Response response mutated according to controller logic
     */
    public function Create(Request $rq)
    {
        $viewModel = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $model = new $this->model();
        $this->Map($model,$viewModel);
        $model = $this->ModelMapping($model,$viewModel,ModelMapping::CREATING,$rq);
        $res = $this->dbContext->Add($model);
        if(!is_null($res))
        {
            $this->Message = "{$this->entityType} added";
            $viewModel->ID = $res->ID;
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::CREATING,$rq);
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$viewModel->ToArray());
            return Extensions::SuccessHandler($rq,$this->Message);
        }
        $this->Message = "{$this->entityType} was not added";
        $rq = $rq
            ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
            ->withAttribute("Error_Entity","Create")
            ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
        return Extensions::ErrorHandler($rq,$this->Message);
    }

    /**
     * @param Request $rq instance of the request
     * @return Response response mutated according to controller logic
     */
    public function Update(Request $rq)
    {
        $viewModel = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $model = new $this->model();
        $this->Map($model,$viewModel);
        $model = $this->ModelMapping($model,$viewModel,ModelMapping::UPDATING,$rq);
        $res = $this->dbContext->Update($model);
        if(!is_null($res))
        {
            $this->Message = "{$this->entityType} updated";
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::UPDATING,$rq);
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$viewModel->ToArray());
            return Extensions::SuccessHandler($rq,$this->Message);
        }
        $this->Message = "{$this->entityType} was not updated";
        $rq = $rq
            ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
            ->withAttribute("Error_Entity","Update")
            ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
        return Extensions::ErrorHandler($rq,$this->Message);
    }

    /**
     * @param Request $rq instance of the request
     * @param Response $rs instance of the response
     * @param array $args route placeholders
     * @return Response response mutated according to controller logic
     */
    public function Delete(Request $rq, Response $rs, $args)
    {
        $viewModel = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $res = $this->dbContext->Delete($args['id']);
        if($res)
        {
            $this->Message = "{$this->entityType} removed";
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::DELETING,$rq);
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$viewModel->ToArray());
            return Extensions::SuccessHandler($rq,$this->Message);
        }
        $this->Message = "{$this->entityType} was not removed";
        $rq = $rq
            ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
            ->withAttribute("Error_Entity","Delete")
            ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
        return Extensions::ErrorHandler($rq,$this->Message);
    }

    /**
     * @param Request $rq instance of the request
     * @param Response $rs instance of the response
     * @param array $args route placeholders
     * @return Response response mutated according to controller logic
     */
    public function Detail(Request $rq, Response $rs, $args)
    {
        /**
         * @var ViewModel $viewModel
         */
        $viewModel = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $res = $this->dbContext->Get($args['id']);
        if($res)
        {
            $this->Message = "{$this->entityType} retrieved successfully";
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::READING,$rq);
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$viewModel->ToArray());
            return Extensions::SuccessHandler($rq,$this->Message);
        }
        $this->Message = "{$this->entityType} was not retrieved";
        $rq = $rq
            ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
            ->withAttribute("Error_Entity","Detail")
            ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
        return Extensions::ErrorHandler($rq,$this->Message);
    }

    /**
     * @param ServerRequestInterface $rq current request instance
     * @return Response response mutated according to controller logic
     */
    public function List(Request $rq)
    {
        $viewModel = $rq->getAttribute(RequestModel::VIEW_MODEL);
        $res = $this->dbContext->GetAll();
        if(!empty($res))
        {
            $this->Message = "{$this->entityType} listing retrieved successfully";
            $viewModels = [];
            foreach($res as $item)
            {
                $vm = $this->ViewModelMapping($item,$viewModel,ModelMapping::READING,$rq);
                $viewModels[] = $vm->ToArray();
            }
            $rq = $rq->withAttribute(RequestModel::PROCESSED_MODEL,$viewModels);
            return Extensions::SuccessHandler($rq,$this->Message);
        }
        $this->Message = "{$this->entityType} listing was not retrieved";
        $rq = $rq
            ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
            ->withAttribute("Error_Entity","List")
            ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
        return Extensions::ErrorHandler($rq,$this->Message);
    }

    /**
     * @param object $model instance of the assigned controller model
     * @param object $viewModel instance of an object representing the input
     * passed to the controller gathered from the request
     * @param int $mappingType enum representing the type of action calling the method
     * eg. (Adding, Deleting, etc....)
     * @param ServerRequestInterface $request current request instance
     * @return object the mutated model
     */
    protected function ModelMapping($model,$viewModel,$mappingType,$request)
    {
        return $model;
    }

    /**
     * @param object $model instance of the assigned controller model
     * @param object $viewModel instance of an object representing the input
     * passed to the controller gathered from the request
     * @param int $mappingType enum representing the type of action calling the method
     * eg. (Adding, Deleting, etc....)
     * * @param ServerRequestInterface $request current request instance
     * @return object the mutated view model
     */
    protected function ViewModelMapping($model,$viewModel,$mappingType,$request)
    {
        $filter = $request->getAttribute(RequestModel::VIEW_MODEL_FILTER);
        $filterType = $request->getAttribute(RequestModel::VIEW_MODEL_FILTER_TYPE,"out");
        $vm = $this->dbContext->GetViewModel($model,$filter,$filterType);
        $keys = $viewModel->ToArray();
        foreach($keys as $key => $val)
            $vm->{$key} = $val;
        return new ViewModel($vm);
    }
}