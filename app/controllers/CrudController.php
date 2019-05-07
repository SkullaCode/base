<?php


namespace App\Controller;

use App\Constant\ErrorCode;
use App\Constant\ModelMapping;
use App\Extension\Extensions;
use App\Interfaces\IDbContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;


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
        $this->model = "";
        $this->dbContext = null;
    }

    /**
     * @param Request $rq instance of the request
     * @param Response $rs instance of the response
     * @return Response response mutated according to controller logic
     */
    public function Create(Request $rq, Response $rs)
    {
        $viewModel = $rq->getAttribute("ViewModel");
        $model = new $this->model();
        $this->Map($model,$viewModel);
        $model = $this->ModelMapping($model,$viewModel,ModelMapping::ADDING,$rq);
        $res = $this->dbContext->Add($model);
        if(!is_null($res))
        {
            $this->Message = "{$this->entityType} added";
            $viewModel->ID = $res->ID;
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::ADDING,$rq);
            $rq = $rq->withAttribute("ProcessedViewModel",$viewModel);
            return Extensions::SuccessHandler($rq,$rs,$this->Message);
        }
        else
        {
            $this->Message = "{$this->entityType} was not added";
            $rq = $rq
                ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
                ->withAttribute("Error_Entity","Input")
                ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
            return Extensions::ErrorHandler($rq,$rs,$this->Message);
        }
    }

    /**
     * @param Request $rq instance of the request
     * @param Response $rs instance of the response
     * @return Response response mutated according to controller logic
     */
    public function Update(Request $rq, Response $rs)
    {
        $viewModel = $rq->getAttribute("ViewModel");
        $model = new $this->model();
        $this->Map($model,$viewModel);
        $model = $this->ModelMapping($model,$viewModel,ModelMapping::UPDATING,$rq);
        $res = $this->dbContext->Update($model);
        if(!is_null($res))
        {
            $this->Message = "{$this->entityType} updated";
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::UPDATING,$rq);
            $rq = $rq->withAttribute("ProcessedViewModel",$viewModel);
            return Extensions::SuccessHandler($rq,$rs,$this->Message);
        }
        else
        {
            $this->Message = "{$this->entityType} was not updated";
            $rq = $rq
                ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
                ->withAttribute("Error_Entity","Input")
                ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
            return Extensions::ErrorHandler($rq,$rs,$this->Message);
        }
    }

    /**
     * @param Request $rq instance of the request
     * @param Response $rs instance of the response
     * @return Response response mutated according to controller logic
     */
    public function Delete(Request $rq, Response $rs)
    {
        $viewModel = $rq->getAttribute("ViewModel");
        $model = new $this->model();
        $this->Map($model,$viewModel);
        $model = $this->ModelMapping($model,$viewModel,ModelMapping::DELETING,$rq);
        $res = $this->dbContext->Delete($model->ID);
        if($res)
        {
            $this->Message = "{$this->entityType} removed";
            $viewModel = $this->ViewModelMapping($res,$viewModel,ModelMapping::DELETING,$rq);
            $rq = $rq->withAttribute("ProcessedViewModel",$viewModel);
            return Extensions::SuccessHandler($rq,$rs,$this->Message);
        }
        else
        {
            $this->Message = "{$this->entityType} was not removed";
            $rq = $rq
                ->withAttribute("Error_Location",strtoupper($this->entityType)." Controller")
                ->withAttribute("Error_Entity","Input")
                ->withAttribute("Error_Code",ErrorCode::INTERNAL_SERVER_ERROR);
            return Extensions::ErrorHandler($rq,$rs,$this->Message);
        }
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
        return $viewModel;
    }
}