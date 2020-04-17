<?php

use App\Extension\Extensions;

date_default_timezone_set('America/Jamaica');
Extensions::init($app->getContainer());
