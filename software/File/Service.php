<?php


namespace Software\File;


use App\Controller\CrudController;
use Psr\Container\ContainerInterface;

class Service extends CrudController
{
    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->entityType = "File";
        $this->model = Model::class;
        $this->dbContext = $this->Context->File;
    }

    protected function ModelMapping($model, $viewModel, $mappingType,$rq)
    {

    }

    protected function ViewModelMapping($model, $viewModel, $mappingType,$rq)
    {

    }
}